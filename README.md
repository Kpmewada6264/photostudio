# PhotoStudio Pro - Professional Photography Website

A complete, production-ready photography studio website built with PHP, MySQL, Bootstrap, and modern web technologies.

## 📸 Features

### Frontend Features
- **Responsive Design** - Works perfectly on all devices
- **Modern UI/UX** - Clean, professional photography theme
- **Hero Slider** - Beautiful image carousel on homepage
- **Gallery System** - Filterable photo gallery with lightbox
- **Booking System** - Online photoshoot booking with form validation
- **Contact Forms** - Secure contact and inquiry forms
- **User Authentication** - Registration and login system
- **SEO Optimized** - Meta tags, semantic HTML, clean URLs
- **Smooth Animations** - Parallax effects, scroll animations
- **Interactive Elements** - Counters, testimonials slider

### Backend Features
- **Admin Dashboard** - Complete admin panel with statistics
- **Booking Management** - Approve/reject/delete bookings
- **Gallery Management** - Upload/delete/categorize photos
- **Service Management** - Add/edit/delete photography packages
- **User Management** - View and manage registered users
- **Database Security** - Prepared statements, input validation
- **Session Management** - Secure user sessions
- **Data Export** - Export bookings and users data

## 🛠 Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8.0+
- **Database**: MySQL 5.7+ / MariaDB 10.2+
- **Server**: Apache (XAMPP compatible)
- **Icons**: Font Awesome 6
- **Fonts**: Google Fonts (Playfair Display, Roboto)
- **Charts**: Chart.js
- **Tables**: DataTables

## 📁 Project Structure

```
photostudio/
│
├── index.php                 # Homepage
├── about.php                 # About page
├── services.php              # Services page
├── gallery.php               # Gallery page
├── booking.php               # Booking page
├── contact.php               # Contact page
├── login.php                 # User login
├── register.php              # User registration
├── logout.php                # User logout
│
├── admin/                    # Admin panel
│   ├── dashboard.php         # Admin dashboard
│   ├── manage_bookings.php   # Manage bookings
│   ├── manage_gallery.php    # Manage gallery
│   ├── manage_services.php   # Manage services
│   └── manage_users.php      # Manage users
│
├── includes/                 # PHP includes
│   ├── db.php               # Database connection
│   ├── header.php           # Header template
│   ├── footer.php           # Footer template
│   └── auth.php             # Authentication functions
│
├── assets/                   # Static assets
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   ├── js/
│   │   └── script.js        # Main JavaScript file
│   └── images/              # Image folders
│       ├── gallery/         # Gallery photos
│       ├── services/        # Service images
│       └── ...             # Other images
│
├── database.sql             # Database schema
└── README.md               # This file
```

## 🚀 Installation Instructions

### Prerequisites
- XAMPP (or similar PHP/MySQL environment)
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache web server
- Modern web browser

### Step 1: Setup XAMPP
1. Download and install XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Start Apache and MySQL services from XAMPP Control Panel
3. Open phpMyAdmin (http://localhost/phpmyadmin)

### Step 2: Create Database
1. In phpMyAdmin, click "New" to create a new database
2. Name the database: `photostudio_pro`
3. Click "Create"

### Step 3: Import Database Schema
1. Select the `photostudio_pro` database
2. Click "Import" tab
3. Choose the `database.sql` file from the project
4. Click "Go" to import

### Step 4: Deploy Project Files
1. Copy the entire `photostudio` folder to:
   - Windows: `C:/xampp/htdocs/`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### Step 5: Configure Database Connection
1. Open `includes/db.php`
2. Verify database credentials:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = ''; // Default XAMPP has no password
   $database = 'photostudio_pro';
   ```

### Step 6: Set File Permissions
Ensure the following folders are writable:
- `assets/images/gallery/`
- `assets/images/services/`

### Step 7: Access the Website
1. Open your web browser
2. Navigate to: `http://localhost/photostudio/`

## 🔑 Default Login Credentials

### Admin Account
- **Email**: `admin@photostudio.com`
- **Password**: `admin123`

### Access Admin Panel
1. Login with admin credentials
2. Click on "Admin" in the navigation menu
3. Access dashboard at: `http://localhost/photostudio/admin/dashboard.php`

## 📸 Adding Sample Images

To make your website look professional, add sample images:

### Gallery Images
1. Add photos to `assets/images/gallery/`
2. Supported formats: JPG, PNG, GIF, WebP
3. Recommended size: 1200x800px
4. Use admin panel to upload and categorize

### Service Images
1. Add service photos to `assets/images/services/`
2. Recommended size: 800x600px
3. Use admin panel to assign to services

### Hero Slider Images
1. Add hero images to `assets/images/`
2. Recommended size: 1920x1080px
3. Update image paths in `index.php`

## ⚙️ Configuration Options

### Email Configuration
To enable email notifications:
1. Open `booking.php` and `contact.php`
2. Uncomment and configure mail() functions
3. Set up SMTP if required

### Google Maps
1. Get API key from [Google Cloud Console](https://console.cloud.google.com)
2. Replace the iframe src in `contact.php` with your location

### Social Media Links
1. Update social media URLs in `includes/footer.php`
2. Add your actual social media profiles

## 🔒 Security Features

- **SQL Injection Protection** - Prepared statements
- **XSS Protection** - Input sanitization
- **CSRF Protection** - Form tokens
- **Password Hashing** - Bcrypt encryption
- **Session Security** - Secure session management
- **File Upload Security** - File type validation
- **Input Validation** - Server-side validation

## 📱 Mobile Responsiveness

The website is fully responsive and works on:
- Desktop computers (1920px+)
- Tablets (768px - 1024px)
- Mobile phones (320px - 768px)

## 🎨 Customization

### Changing Colors
Edit CSS variables in `assets/css/style.css`:
```css
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    /* ... other colors */
}
```

### Changing Fonts
Update Google Fonts import in `includes/header.php`

### Adding New Pages
1. Create new PHP file
2. Include header and footer:
   ```php
   <?php require_once 'includes/header.php'; ?>
   <!-- Your content here -->
   <?php require_once 'includes/footer.php'; ?>
   ```

## 🚀 Performance Optimization

- **Image Optimization** - Uploads are resized to 1600px, converted to WebP (quality 82) and stored with a 400px thumbnail
- **Lazy Loading** - Gallery and content images use `loading="lazy"` / `decoding="async"`; hero slides load immediately
- **Thumbnails** - Grids load `thumbnails/*.webp`, full images only open in the lightbox
- **Caching** - `.htaccess` sets long-lived cache headers for images, CSS and JS
- **CDN Ready** - External resources use CDN

Existing images can be converted in bulk:

```bash
php tools/optimize_images.php                       # write .webp + thumbnails next to originals
php tools/optimize_images.php --delete-originals    # also remove the source JPG/PNG files
```

Pages resolve image URLs through `imageSrc()` in `includes/image_optimizer.php`, which prefers the WebP
variant and thumbnail when they exist, so no database change is needed after a bulk conversion.

## 📊 Admin Features

### Dashboard Statistics
- Total bookings count
- Total users count
- Gallery photos count
- Active services count
- Booking status charts
- Service popularity analytics

### Management Tools
- **Bookings**: View, approve, reject, delete bookings
- **Gallery**: Upload, categorize, delete photos
- **Services**: Add, edit, delete photography packages
- **Users**: View registered users, export data

## 🔧 Troubleshooting

### Common Issues

**Database Connection Error**
- Check XAMPP MySQL service is running
- Verify database credentials in `includes/db.php`
- Ensure database `photostudio_pro` exists

**Images Not Displaying**
- Check file permissions on image folders
- Verify image paths in HTML
- Ensure images are in correct folders

**Admin Login Not Working**
- Verify admin user exists in database
- Check password hash in database
- Clear browser cookies and cache

**404 Errors**
- Ensure .htaccess is present (if using pretty URLs)
- Check file names and paths
- Verify Apache mod_rewrite is enabled

### Error Reporting
To enable error reporting for development:
```php
// Add to top of PHP files
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

## 📞 Support

For support and questions:
1. Check this README file
2. Review code comments
3. Test with default XAMPP setup
4. Verify all installation steps

## 📄 License

This project is for educational and demonstration purposes. Feel free to modify and use for your own photography studio website.

## 🎯 Next Steps

1. **Add Your Content** - Replace sample text with your studio information
2. **Upload Your Photos** - Add your actual photography work
3. **Configure Email** - Set up email notifications
4. **Test All Features** - Verify booking, contact, and admin functions
5. **Launch Website** - Deploy to production server

---

**PhotoStudio Pro** - Professional Photography Website Solution
Built with ❤️ for photographers by photographers
