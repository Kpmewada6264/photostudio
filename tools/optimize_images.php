<?php
/**
 * Backfill script: resize existing images, write WebP copies and thumbnails.
 *
 * Usage:
 *   php tools/optimize_images.php [directory ...] [--delete-originals]
 *
 * Without arguments it processes assets/images, assets/images/gallery and
 * assets/images/services. Pages keep working without a database change because
 * imageSrc() prefers the generated .webp variant when it exists.
 */

require_once __DIR__ . '/../includes/image_optimizer.php';

if (PHP_SAPI !== 'cli') {
    exit("This script must be run from the command line.\n");
}

if (!imageOptimizerAvailable()) {
    exit("PHP GD with WebP support is required.\n");
}

$root = dirname(__DIR__);
$delete_originals = false;
$directories = [];

foreach (array_slice($argv, 1) as $argument) {
    if ($argument === '--delete-originals') {
        $delete_originals = true;
    } else {
        $directories[] = $argument;
    }
}

if (!$directories) {
    $directories = ['assets/images', 'assets/images/gallery', 'assets/images/services'];
}

$converted = 0;
$saved_bytes = 0;

foreach ($directories as $directory) {
    $path = $root . '/' . trim($directory, '/');
    if (!is_dir($path)) {
        continue;
    }

    foreach (glob($path . '/*') as $file) {
        if (!is_file($file)) {
            continue;
        }

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            continue;
        }

        $base_name = pathinfo($file, PATHINFO_FILENAME);
        $webp_path = $path . '/' . $base_name . '.webp';
        $thumb_path = $path . '/' . IMAGE_THUMB_DIR . '/' . $base_name . '.webp';

        $contents = file_get_contents($file);
        $source = $contents === false ? false : @imagecreatefromstring($contents);
        if (!$source) {
            fwrite(STDERR, "Skipped unreadable image: $file\n");
            continue;
        }

        saveResizedWebp($source, $webp_path, IMAGE_MAX_WIDTH);
        saveResizedWebp($source, $thumb_path, IMAGE_THUMB_WIDTH);
        imagedestroy($source);

        $original_size = filesize($file);
        $new_size = file_exists($webp_path) ? filesize($webp_path) : $original_size;
        $saved_bytes += max(0, $original_size - $new_size);
        $converted++;

        printf("%s: %d KB -> %d KB\n", basename($file), $original_size / 1024, $new_size / 1024);

        if ($delete_originals && $extension !== 'webp' && file_exists($webp_path)) {
            unlink($file);
        }
    }
}

printf("Converted %d image(s), saved %d KB.\n", $converted, $saved_bytes / 1024);
