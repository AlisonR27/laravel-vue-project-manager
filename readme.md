# Laravel + Vue Project Manager (Hiring Test)

This is the **Laravel + Vue** project made for the Cloud++ Hiring Test.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- Database (MySQL/PostgreSQL/SQLite) configured in `.env`

## Setup

1. Clone the repository and enter the project folder.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Copy the environment file and adjust variables (DB_*, APP_*):
   ```bash
   cp .env.example .env
   ```
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

## Run the project

Run the following commands in order:

```bash
php artisan migrate:fresh
php artisan db:seed
php artisan serve
npm install
```

Next, you can choose between building and serving (see my flaws as 3 times more warnings on the console)
```bash
npm run build # for static
npm run dev # for develop serving with vite
```

- The app will be available at `http://127.0.0.1:8000` || `http://localhost:8000`.

## Default User Credentials

The database seeder creates a default user. Use these credentials to log in:

- **Name:** John Doe
- **Email:** admin@example.com
- **Password:** password

## Tests

Run all tests with:

```bash
php artisan test
```

This will execute **Laravel starter kit default tests** as well as a **custom test**.
**Custom tests:**
- `tests/Feature/AddProjectTest.php`
- `tests/Unit/ProjectCreateTest.php`

Run only the custom test with:

```bash
php artisan test --filter=AddProjectTest
php artisan test --filter=ProjectCreateTest
# or
php artisan test tests/Feature/AddProjectTest.php
php artisan test tests/Unit/AddProjectTest.php
```

## Useful scripts

Reset the database and seed again:

```bash
php artisan migrate:fresh --seed
```

## Notes

- Ensure your database credentials in `.env` are correct before running migrations/seeds.
- I made it using postgres, if using SQLite, remember to set `DB_CONNECTION=sqlite` and create the database file if needed.
