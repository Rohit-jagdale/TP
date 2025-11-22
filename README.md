# PHP Blog Backend

A simple, dynamic blog system built with PHP and MySQL.

## Features

- **Dynamic Content**: Posts and categories managed through database
- **Admin Panel**: Simple admin interface for managing posts and categories
- **Responsive Design**: Works on all devices
- **SEO Friendly**: Clean URLs and proper meta tags

## Installation

### 1. Database Setup

1. Create a MySQL database
2. Import the schema:
   ```bash
   mysql -u root -p blog_db < database/schema.sql
   ```
   Or use phpMyAdmin to import `database/schema.sql`

### 2. Configuration

Edit `backend/config/database.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. File Permissions

Ensure the uploads directory is writable:

```bash
mkdir uploads
chmod 755 uploads
```

### 4. Default Admin Credentials

- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change the default password in production!

To change the password, update it in the database:
```sql
UPDATE admin_users SET password = '$2y$10$...' WHERE username = 'admin';
```
(Use `password_hash('your_new_password', PASSWORD_DEFAULT)` in PHP to generate the hash)

## File Structure

```
/
├── backend/
│   ├── config/
│   │   ├── config.php          # Application configuration
│   │   └── database.php        # Database connection
│   ├── includes/
│   │   └── functions.php       # Helper functions
│   └── admin/
│       ├── login.php           # Admin login page
│       ├── index.php           # Admin dashboard
│       ├── posts.php           # Post management
│       └── categories.php      # Category management
├── database/
│   └── schema.sql              # Database schema
├── uploads/                     # Uploaded images (create this)
├── index.php                    # Homepage
├── posts.php                    # Posts listing
├── single-post.php             # Single post view
└── .htaccess                    # Apache configuration
```

## Usage

### Admin Panel

Access the admin panel at: `http://yourdomain.com/backend/admin/`

1. Login with default credentials
2. Create categories
3. Create and publish posts
4. Upload featured images

### Frontend

- **Homepage**: `index.php` - Shows latest posts
- **All Posts**: `posts.php` - Lists all published posts
- **Single Post**: `single-post.php?slug=post-slug` - Individual post view

## Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB
- Apache with mod_rewrite (optional, for clean URLs)
- PDO extension enabled

## Security Notes

1. Change default admin password
2. Update database credentials
3. Set proper file permissions
4. Use HTTPS in production
5. Regularly update PHP and MySQL
6. Consider adding CSRF protection for admin forms

## Troubleshooting

### Database Connection Error
- Check database credentials in `backend/config/database.php`
- Ensure MySQL service is running
- Verify database exists

### Images Not Uploading
- Check `uploads/` directory exists and is writable
- Verify PHP upload settings in php.ini
- Check file permissions

### 404 Errors
- Ensure `.htaccess` is working (if using clean URLs)
- Check Apache mod_rewrite is enabled
- Verify file paths are correct

## License

This project is open source and available for personal and commercial use.

