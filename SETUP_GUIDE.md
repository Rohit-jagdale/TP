# Step-by-Step Setup Guide

Follow these steps to get your PHP blog up and running.

## Step 1: Set Up the Database

### Option A: Using Command Line (Terminal)

1. Open Terminal (on Mac) or Command Prompt (on Windows)
2. Navigate to your project directory:
   ```bash
   cd /Users/rohit/Desktop/TP
   ```
3. Run the MySQL command:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   - You'll be prompted for your MySQL root password
   - Enter your password (it won't show as you type - that's normal)
   - Press Enter

### Option B: Using phpMyAdmin (Easier for beginners)

1. Open phpMyAdmin in your browser (usually at `http://localhost/phpmyadmin`)
2. Click on "New" in the left sidebar to create a new database
3. Enter database name: `blog_db`
4. Choose collation: `utf8mb4_unicode_ci`
5. Click "Create"
6. Select the `blog_db` database from the left sidebar
7. Click on the "Import" tab at the top
8. Click "Choose File" and select `database/schema.sql` from your project folder
9. Scroll down and click "Go"
10. You should see a success message

### Verify Database Setup

Run this in MySQL or phpMyAdmin SQL tab:

```sql
SHOW TABLES;
```

You should see:

- `admin_users`
- `categories`
- `post_categories`
- `posts`

---

## Step 2: Configure Database Credentials

1. Open the file: `backend/config/database.php`
2. Update these lines with your MySQL credentials:

```php
define('DB_HOST', 'localhost');      // Usually 'localhost'
define('DB_NAME', 'blog_db');        // The database name you created
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', 'your_password');  // Your MySQL password
```

**Common scenarios:**

- **XAMPP/MAMP default**: Username is `root`, password is usually empty `''`
- **MAMP**: Username is `root`, password is `root`
- **Custom setup**: Use your MySQL username and password

**Example for MAMP:**

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');
```

**Example for XAMPP (default):**

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

---

## Step 3: Create Uploads Directory

### Option A: Using Terminal/Command Prompt

1. Navigate to your project folder:
   ```bash
   cd /Users/rohit/Desktop/TP
   ```
2. Create the directory:
   ```bash
   mkdir uploads
   ```
3. Set permissions (Mac/Linux):
   ```bash
   chmod 755 uploads
   ```

### Option B: Using File Manager

1. Open your project folder in Finder (Mac) or File Explorer (Windows)
2. Right-click in the folder
3. Create a new folder named `uploads`
4. That's it! (Permissions are usually fine by default)

---

## Step 4: Start Your Web Server

### Using XAMPP:

1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL
4. Your site will be at: `http://localhost/TP/`

### Using MAMP:

1. Open MAMP
2. Click "Start Servers"
3. Your site will be at: `http://localhost:8888/TP/` (or check MAMP's port settings)

### Using PHP Built-in Server (for testing):

1. Open Terminal in your project folder:
   ```bash
   cd /Users/rohit/Desktop/TP
   ```
2. Run:
   ```bash
   php -S localhost:8000
   ```
3. Your site will be at: `http://localhost:8000/`

---

## Step 5: Set Up Admin User

Before you can log in, you need to set up the admin user account. This ensures the password is properly hashed in the database.

1. Open your browser
2. Go to: `http://localhost:8000/backend/admin/setup-admin.php` (adjust URL based on your setup)
   - If using XAMPP: `http://localhost/TP/backend/admin/setup-admin.php`
   - If using MAMP: `http://localhost:8888/TP/backend/admin/setup-admin.php`
3. You should see a success message confirming the admin user was created/updated
4. **Important**: Delete the `setup-admin.php` file after use for security

---

## Step 6: Access the Admin Panel

1. Open your browser
2. Go to: `http://localhost:8000/backend/admin/login.php` (adjust URL based on your setup)
   - If using XAMPP: `http://localhost/TP/backend/admin/login.php`
   - If using MAMP: `http://localhost:8888/TP/backend/admin/login.php`
3. You should see the login page
4. Login with:
   - **Username**: `admin`
   - **Password**: `admin123`

---

## Step 7: Test Everything

### Test Admin Login:

1. Go to admin login page
2. Enter credentials
3. You should see the dashboard

### Create Your First Category:

1. In admin panel, click "Manage Categories"
2. Enter a category name (e.g., "Tech")
3. Add description (optional)
4. Click "Create Category"

### Create Your First Post:

1. Click "Manage Posts"
2. Click to create a new post
3. Fill in:
   - Title: "My First Blog Post"
   - Content: Write some content
   - Excerpt: Short summary (optional)
   - Select a category
   - Upload a featured image (optional)
   - Set status to "Published"
4. Click "Create Post"

### View Your Blog:

1. Go to: `http://localhost/TP/index.php`
2. You should see your post on the homepage!

---

## Troubleshooting

### "Database connection failed" error:

- Check your database credentials in `backend/config/database.php`
- Make sure MySQL is running
- Verify the database `blog_db` exists

### "404 Not Found" error:

- Check your web server is running (Apache/XAMPP/MAMP)
- Verify the URL path is correct
- Make sure you're accessing `.php` files, not `.html`

### "Call to undefined function" error:

- If you see errors like "Call to undefined function isLoggedIn()", make sure all required files are included
- This should be fixed automatically, but if it persists, check that `backend/includes/functions.php` exists

### Images not uploading:

- Make sure `uploads/` folder exists
- Check folder permissions (should be 755 or 777)
- Verify PHP upload settings in `php.ini`

### Can't login to admin:

- **First, run the setup script**: Go to `http://localhost:8000/backend/admin/setup-admin.php` to create/update the admin user with the correct password hash
- Default credentials: `admin` / `admin123`
- Check database was imported correctly
- Verify `admin_users` table has data
- Make sure you've deleted `setup-admin.php` after running it (it should only be run once)

### Still having issues?

1. Check PHP error logs
2. Enable error display in `backend/config/config.php` (already enabled)
3. Check browser console for JavaScript errors
4. Verify all files are in the correct locations

---

## Security Reminder

**Before going live:**

1. **Delete `setup-admin.php`** - This file should never be accessible in production
2. Change the admin password in the database (or use the setup script with a new password)
3. Update database credentials
4. Disable error display in production
5. Use HTTPS
6. Set proper file permissions

---

## Next Steps After Setup

1. ✅ Create categories for your blog topics
2. ✅ Write and publish your first posts
3. ✅ Customize the design (edit CSS files)
4. ✅ Add more features as needed

Enjoy your new blog! 🎉
