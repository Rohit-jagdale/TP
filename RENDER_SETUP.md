# Render Deployment Setup Guide

This guide will help you deploy your PHP blog to Render.com.

## Prerequisites

1. A Render.com account (free tier available)
2. A PostgreSQL database (Render's default, or use external service)

## Step 1: Create a PostgreSQL Database on Render

1. Go to your Render dashboard
2. Click "New +" → "PostgreSQL"
3. Choose a name for your database (e.g., "blog-db")
4. Select a plan (Free tier available for development)
5. Note down the following connection details from Render's dashboard:
   - **Internal Database URL** (for services in same region) - format: `postgresql://user:pass@host:port/dbname`
   - **External Database URL** (for external connections)
   - **Host** (extracted from URL, e.g., `dpg-xxxxx-a.render.com`)
   - **Database Name**
   - **Username**
   - **Password**
   - **Port** (usually 5432 for PostgreSQL)

## Step 2: Import Database Schema

1. Connect to your PostgreSQL database using:

   - **psql command line**: `psql "postgresql://user:pass@host:port/dbname" -f database/schema-postgres.sql`
   - **pgAdmin** (GUI tool)
   - **Render's database console**: Go to your database in Render dashboard → "Connect" → "psql" tab, then paste and run the SQL from `database/schema-postgres.sql`

2. **Important**: Use `database/schema-postgres.sql` (not `schema.sql`) for PostgreSQL!

## Step 3: Deploy Web Service

1. In Render dashboard, click "New +" → "Web Service"
2. Connect your GitHub repository (or use Render's CLI)
3. Configure the service:

### Build Settings:

- **Build Command**: (leave empty or use `echo "No build needed"`)
- **Start Command**: `apache2-foreground` (or leave empty, Dockerfile handles this)

### Environment Variables:

Add these environment variables in Render's dashboard:

```
DB_HOST=your-database-host.render.com
DB_NAME=blog_db
DB_USER=your-database-user
DB_PASS=your-database-password
DB_PORT=5432
DB_TYPE=pgsql
```

**Important**:

- If your database is on Render in the same region, use the **Internal Database URL** host
- If using external database, use the **External Database URL** host
- **DB_TYPE** should be set to `pgsql` for PostgreSQL (or `mysql` for MySQL)
- The code will auto-detect PostgreSQL if hostname contains "postgres", but it's better to set `DB_TYPE` explicitly
- **DB_PORT** is optional (defaults to 5432 for PostgreSQL, 3306 for MySQL)
- Never commit passwords to git!

**Note**: The application automatically detects PostgreSQL vs MySQL based on the hostname or `DB_TYPE` environment variable.

### Advanced Settings:

- **Dockerfile Path**: `Dockerfile` (if using Docker)
- **Docker Context**: `.` (root directory)

## Step 4: Configure File Uploads

The `uploads/` directory needs to be writable. The Dockerfile sets this up, but if you need persistent storage:

1. Consider using Render's disk storage (if available)
2. Or use a cloud storage service (AWS S3, Cloudinary, etc.) for uploads

## Step 5: Verify Deployment

1. Visit your Render service URL
2. Check that the homepage loads
3. Try accessing `/posts.php`
4. Test admin login at `/backend/admin/login.php`

## Troubleshooting

### Database Connection Errors

**Error**: `SQLSTATE[HY000] [2002] No such file or directory`

**Solution**:

- Check that `DB_HOST` environment variable is set correctly
- Use the full hostname, not `localhost`
- Verify database credentials are correct
- Ensure database is accessible from your web service

### 500 Internal Server Error

**Check Render logs**:

1. Go to your service in Render dashboard
2. Click on "Logs" tab
3. Look for PHP errors or database connection errors

### Images Not Uploading

1. Verify `uploads/` directory has write permissions
2. Check PHP upload settings in Dockerfile
3. Ensure sufficient disk space

## Environment Variables Reference

| Variable     | Description                     | Example                  | Required |
| ------------ | ------------------------------- | ------------------------ | -------- |
| `DB_HOST`    | Database hostname               | `dpg-xxxxx-a.render.com` | Yes      |
| `DB_NAME`    | Database name                   | `blog_db`                | Yes      |
| `DB_USER`    | Database username               | `blog_user`              | Yes      |
| `DB_PASS`    | Database password               | `your_secure_password`   | Yes      |
| `DB_TYPE`    | Database type (`pgsql`/`mysql`) | `pgsql`                  | No\*     |
| `DB_PORT`    | Database port                   | `5432` (PostgreSQL)      | No       |
| `DB_CHARSET` | Character set (MySQL only)      | `utf8mb4`                | No       |

\* Auto-detected from hostname if not set, but recommended to set explicitly

## Security Notes

1. **Never commit** `.env` files or hardcoded credentials
2. Use Render's **Environment Variables** feature for secrets
3. Enable **HTTPS** in Render settings (usually automatic)
4. Change default admin password after first login
5. Regularly update dependencies

## Database Type Support

This application supports both **PostgreSQL** and **MySQL**:

### PostgreSQL (Recommended for Render)

- Use `database/schema-postgres.sql` for schema
- Set `DB_TYPE=pgsql` (or let it auto-detect)
- Default port: 5432

### MySQL

- Use `database/schema.sql` for schema
- Set `DB_TYPE=mysql` (or let it auto-detect)
- Default port: 3306
- Requires `DB_CHARSET` (usually `utf8mb4`)

### Using External Database Services

You can use external database services:

**PostgreSQL**: AWS RDS, Heroku Postgres, Supabase, etc.
**MySQL**: PlanetScale, AWS RDS, etc.

1. Get connection details from your database provider
2. Set environment variables in Render with those credentials
3. Ensure your database provider allows connections from Render's IP ranges
4. Use the appropriate schema file (`schema-postgres.sql` or `schema.sql`)

## Support

- Render Documentation: https://render.com/docs
- Render Community: https://community.render.com
