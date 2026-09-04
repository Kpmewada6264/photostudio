<?php
/**
 * Image optimization helpers.
 *
 * Uploaded photos are resized, converted to WebP and stored together with a
 * smaller thumbnail used by the gallery grids.
 */

define('IMAGE_MAX_WIDTH', 1600);
define('IMAGE_THUMB_WIDTH', 400);
define('IMAGE_WEBP_QUALITY', 82);
define('IMAGE_THUMB_DIR', 'thumbnails');

function imageOptimizerAvailable() {
    return extension_loaded('gd') && function_exists('imagewebp');
}

/**
 * Resize $source so its width does not exceed $max_width and write it as WebP.
 */
function saveResizedWebp($source, $destination, $max_width, $quality = IMAGE_WEBP_QUALITY) {
    $width = imagesx($source);
    $height = imagesy($source);

    if ($width > $max_width) {
        $new_width = $max_width;
        $new_height = (int) round($height * ($max_width / $width));
    } else {
        $new_width = $width;
        $new_height = $height;
    }

    $resized = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    imagecopyresampled($resized, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $directory = dirname($destination);
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    $saved = imagewebp($resized, $destination, $quality);
    imagedestroy($resized);

    return $saved;
}

/**
 * Store an uploaded image as an optimized WebP plus a thumbnail.
 *
 * Returns ['success' => bool, 'file_name' => string, 'error' => string].
 */
function storeOptimizedUpload($tmp_path, $original_name, $target_dir) {
    $target_dir = rtrim($target_dir, '/');
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $base_name = pathinfo(basename($original_name), PATHINFO_FILENAME);
    $base_name = preg_replace('/[^A-Za-z0-9_-]+/', '-', $base_name);
    $base_name = trim($base_name, '-');
    if ($base_name === '') {
        $base_name = 'photo';
    }

    if (!imageOptimizerAvailable()) {
        $file_name = time() . '_' . basename($original_name);
        if (move_uploaded_file($tmp_path, $target_dir . '/' . $file_name)) {
            return ['success' => true, 'file_name' => $file_name, 'error' => ''];
        }
        return ['success' => false, 'file_name' => '', 'error' => 'Failed to upload image'];
    }

    $contents = file_get_contents($tmp_path);
    $source = $contents === false ? false : @imagecreatefromstring($contents);
    if (!$source) {
        return ['success' => false, 'file_name' => '', 'error' => 'Unsupported or corrupted image file'];
    }

    $file_name = time() . '_' . $base_name . '.webp';
    $full_saved = saveResizedWebp($source, $target_dir . '/' . $file_name, IMAGE_MAX_WIDTH);
    $thumb_saved = saveResizedWebp($source, $target_dir . '/' . IMAGE_THUMB_DIR . '/' . $file_name, IMAGE_THUMB_WIDTH);
    imagedestroy($source);

    if (!$full_saved) {
        return ['success' => false, 'file_name' => '', 'error' => 'Failed to process image'];
    }
    if (!$thumb_saved) {
        return ['success' => true, 'file_name' => $file_name, 'error' => ''];
    }

    return ['success' => true, 'file_name' => $file_name, 'error' => ''];
}

/**
 * URL of an image, preferring the WebP variant and, when requested, the thumbnail.
 *
 * $dir is relative to the project root (e.g. 'assets/images/gallery') and
 * $url_prefix is prepended to the returned URL (e.g. '../' inside /admin).
 */
function imageSrc($dir, $file_name, $thumbnail = false, $url_prefix = '') {
    $dir = trim($dir, '/');
    $root = dirname(__DIR__);
    $base_name = pathinfo($file_name, PATHINFO_FILENAME);

    $candidates = [];
    if ($thumbnail) {
        $candidates[] = $dir . '/' . IMAGE_THUMB_DIR . '/' . $base_name . '.webp';
        $candidates[] = $dir . '/' . IMAGE_THUMB_DIR . '/' . $file_name;
    }
    $candidates[] = $dir . '/' . $base_name . '.webp';
    $candidates[] = $dir . '/' . $file_name;

    foreach ($candidates as $candidate) {
        if (is_file($root . '/' . $candidate)) {
            return $url_prefix . $candidate;
        }
    }

    return $url_prefix . $dir . '/' . $file_name;
}

/**
 * Remove an image together with its WebP and thumbnail variants.
 */
function deleteImageVariants($dir, $file_name) {
    $dir = rtrim($dir, '/');
    $base_name = pathinfo($file_name, PATHINFO_FILENAME);

    $paths = [
        $dir . '/' . $file_name,
        $dir . '/' . $base_name . '.webp',
        $dir . '/' . IMAGE_THUMB_DIR . '/' . $file_name,
        $dir . '/' . IMAGE_THUMB_DIR . '/' . $base_name . '.webp',
    ];

    foreach (array_unique($paths) as $path) {
        if (is_file($path)) {
            unlink($path);
        }
    }
}
