# PostgreSQL Migration Guide

This guide explains the changes made to support PostgreSQL in addition to MySQL.

## What Changed

### 1. Database Configuration (`backend/config/database.php`)

- Now supports both MySQL and PostgreSQL
- Auto-detects database type from hostname or uses `DB_TYPE` environment variable
- Uses appropriate PDO DSN for each database type

### 2. PostgreSQL Schema (`database/schema-postgres.sql`)

- Created new schema file specifically for PostgreSQL
- Key differences from MySQL:
  - `AUTO_INCREMENT` → `SERIAL`
  - `ENUM` → `CHECK` constraint
  - `ON UPDATE CURRENT_TIMESTAMP` → PostgreSQL trigger
  - `ON DUPLICATE KEY UPDATE` → `ON CONFLICT DO UPDATE`
  - Removed MySQL-specific `ENGINE` and `CHARSET` clauses

### 3. Dockerfile

- Added `libpq-dev` package for PostgreSQL support
- Added `pdo_pgsql` PHP extension

### 4. Docker Compose

- Created `docker-compose-postgres.yml` for PostgreSQL setup
- Original `docker-compose.yml` still uses MySQL

## Quick Start with PostgreSQL

### On Render.com

1. Create a PostgreSQL database in Render
2. Set environment variables:
   ```
   DB_HOST=your-postgres-host.render.com
   DB_NAME=blog_db
   DB_USER=your-user
   DB_PASS=your-password
   DB_TYPE=pgsql
   DB_PORT=5432
   ```
3. Import schema: Use `database/schema-postgres.sql`

### Local Development with Docker

```bash
# Use PostgreSQL version
docker-compose -f docker-compose-postgres.yml up -d
```

### Local Development with MySQL (Original)

```bash
# Use MySQL version
docker-compose up -d
```

## Environment Variables

| Variable     | PostgreSQL | MySQL     | Notes                    |
| ------------ | ---------- | --------- | ------------------------ |
| `DB_TYPE`    | `pgsql`    | `mysql`   | Auto-detected if not set |
| `DB_PORT`    | `5432`     | `3306`    | Optional, defaults apply |
| `DB_CHARSET` | Not used   | `utf8mb4` | MySQL only               |

## Schema Differences

### Auto-increment IDs

- **MySQL**: `id INT AUTO_INCREMENT PRIMARY KEY`
- **PostgreSQL**: `id SERIAL PRIMARY KEY`

### ENUM Types

- **MySQL**: `status ENUM('draft', 'published')`
- **PostgreSQL**: `status VARCHAR(20) CHECK (status IN ('draft', 'published'))`

### Timestamps

- **MySQL**: `updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP`
- **PostgreSQL**: Uses trigger function `update_updated_at_column()`

### Upsert (Insert or Update)

- **MySQL**: `ON DUPLICATE KEY UPDATE`
- **PostgreSQL**: `ON CONFLICT (column) DO UPDATE`

## Testing

The application code is database-agnostic and works with both MySQL and PostgreSQL. All queries use PDO prepared statements which are compatible with both.

## Rollback to MySQL

If you need to switch back to MySQL:

1. Set `DB_TYPE=mysql` (or remove it and ensure hostname doesn't contain "postgres")
2. Use `database/schema.sql` instead of `schema-postgres.sql`
3. Ensure MySQL extensions are installed (already in Dockerfile)

## Support

Both database types are fully supported. Choose based on:

- **PostgreSQL**: Better for cloud deployments (Render, Heroku)
- **MySQL**: Better for traditional hosting, XAMPP, MAMP
