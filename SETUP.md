# PhotoStudio Pro - Quick Setup Guide

## 🚀 5-Minute Quick Start

### 1. Install XAMPP
- Download from: https://www.apachefriends.org
- Install with default settings
- Start Apache and MySQL services

### 2. Create Database
- Open: http://localhost/phpmyadmin
- Click "New" → Database name: `photostudio_pro`
- Click "Create"

### 3. Import Database
- Select `photostudio_pro` database
- Click "Import" → Choose `database.sql` file
- Click "Go"

### 4. Copy Files
- Copy `photostudio` folder to `C:/xampp/htdocs/`
- Navigate to: http://localhost/photostudio/

### 5. Login as Admin
- Go to: http://localhost/photostudio/login.php
- Email: `admin@photostudio.com`
- Password: `admin123`

## 📸 Adding Your First Gallery Photos

1. **Login as Admin**
2. **Go to Admin → Manage Gallery**
3. **Click "Upload New Photo"**
4. **Select Category** (Wedding, Portrait, etc.)
5. **Choose Photo File** (JPG, PNG recommended)
6. **Click "Upload Photo"**

## 🎯 Testing Your Website

### Test User Registration
1. Go to: http://localhost/photostudio/register.php
2. Fill registration form
3. Verify login works

### Test Booking System
1. Go to: http://localhost/photostudio/booking.php
2. Fill booking form
3. Check admin panel for new booking

### Test Contact Form
1. Go to: http://localhost/photostudio/contact.php
2. Send test message
3. Check results

## 🔧 Common Setup Issues & Solutions

### Issue: "Database Connection Failed"
**Solution:**
- Check if MySQL is running in XAMPP Control Panel
- Verify database name is exactly `photostudio_pro`
- Check `includes/db.php` credentials

### Issue: "Images Not Showing"
**Solution:**
- Create folders: `assets/images/gallery/` and `assets/images/services/`
- Set folder permissions (right-click → Properties → Security)
- Add sample images to test

### Issue: "Admin Login Not Working"
**Solution:**
- Import database.sql again
- Check admin user exists in phpMyAdmin
- Clear browser cache

### Issue: "404 Page Not Found"
**Solution:**
- Ensure files are in correct `htdocs/photostudio/` folder
- Check Apache is running
- Try: http://localhost/photostudio/index.php

## 📱 Mobile Testing

Test on mobile devices:
1. Open browser developer tools (F12)
2. Click device toggle icon
3. Test different screen sizes
4. Verify responsive design

## 🎨 Customizing Your Website

### Change Studio Name
Edit `includes/header.php`:
```php
<a class="navbar-brand" href="index.php">
    <i class="fas fa-camera"></i> Your Studio Name
</a>
```

### Change Contact Information
Edit `includes/footer.php`:
```html
<li><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</li>
<li><i class="fas fa-envelope me-2"></i>info@yourstudio.com</li>
```

### Change Colors
Edit `assets/css/style.css`:
```css
:root {
    --primary-color: #your-color;
    --secondary-color: #your-color;
}
```

## 📊 Admin Panel Features

### Dashboard
- View booking statistics
- Monitor user registrations
- Check gallery photo count

### Manage Bookings
- Approve/reject booking requests
- View customer details
- Export booking data

### Manage Gallery
- Upload photos to categories
- Delete unwanted photos
- Organize by event type

### Manage Services
- Add new photography packages
- Edit prices and descriptions
- Update service images

## 🔒 Security Tips

1. **Change Admin Password**
   - Login to phpMyAdmin
   - Browse `users` table
   - Edit admin password hash

2. **Enable HTTPS** (Production only)
   - Install SSL certificate
   - Update .htaccess for HTTPS redirect

3. **Regular Backups**
   - Export database regularly
   - Backup image folders
   - Keep copy of source code

## 📞 Getting Help

### Check These First:
1. XAMPP services are running
2. Database imported correctly
3. Files in correct location
4. No typos in URLs

### Still Having Issues?
1. Check browser console (F12) for errors
2. Verify PHP error logs in XAMPP
3. Test with different browsers
4. Restart XAMPP services

## 🚀 Going Live (Production)

### Before Launch:
1. Replace sample content
2. Add your actual photos
3. Configure email settings
4. Test all forms
5. Check mobile responsiveness

### Production Server:
1. Upload files to web server
2. Create MySQL database
3. Import database schema
4. Update database credentials
5. Set file permissions
6. Configure domain name

---

**Your PhotoStudio Pro website is now ready!** 📸✨

For detailed documentation, see the main README.md file.
