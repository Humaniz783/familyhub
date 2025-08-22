# familyhub

An app to manage family affairs.

## Setup

1. Create a MySQL database and run the schema:
   ```sql
   source schema.sql;
   ```
2. Configure database credentials using environment variables:
   - `MYSQL_HOST`
   - `MYSQL_DATABASE`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
3. Run the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Navigate to `http://localhost:8000/index.php` to use the app.

The shopping list and task sections allow adding and deleting items stored in MySQL.
