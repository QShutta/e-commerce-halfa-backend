# E-commerce-Halfa (PHP Backend)

A lightweight PHP backend providing REST endpoints for a mobile/web e-commerce app: products, categories, carts, orders, users, coupons, favorites, and image upload. Integrates Firebase Cloud Messaging for push notifications and PHPMailer for email.

## Why this project
- Rapid API backend for a Flutter (or other) client.
- Small, self-contained codebase to learn and iterate fast.
- Uses Composer packages (Google Auth + Guzzle) to send FCM v1 messages securely.

## Key features
- Products, categories, and search endpoints
- Cart and order management
- User authentication and verification flows
- Coupons and favorites management
- Image upload and user profile pictures
- Push notifications via Firebase Cloud Messaging
- Email sending via PHPMailer

## Tech stack
- PHP (plain PHP with PDO)
- MySQL
- Composer packages: `google/auth`, `guzzlehttp/guzzle`, PHPMailer
- Google Firebase (FCM) for push

## Project layout (important files)
- `functions.php` — shared helpers, DB helpers, and `sendFcmNotification` implementation
- `connect.php` — database connection config
- `test.php` — quick script to test FCM call
- `vendor/` — Composer packages
- `PHPMailer/` — included PHPMailer sources
- `privite_files/` — (recommended) store the Firebase service account JSON here (outside webroot)

## Requirements
- PHP 7.4+ (match your server's PHP version)
- Extensions: `curl`, `openssl`, `json`, `pdo_mysql`
- Composer (to install vendor packages)
- A Firebase service account JSON with Cloud Messaging enabled

## Installation (server / remote)
1. Upload project to your server (outside or inside your webroot as needed).
2. From project root run:
   ```bash
   composer install
   ```
3. Ensure PHP extensions are enabled and restart your web server (Apache/nginx + php-fpm).
4. Place the Firebase service account JSON in a secure location (outside public webroot), e.g.:
   `/var/www/secure/service-account.json` or `privite_files/service-acount.json` in project root.
5. Set environment variable (recommended):
   ```bash
   export GOOGLE_APPLICATION_CREDENTIALS=/path/to/service-account.json
   ```
   Or configure it for `php-fpm`/systemd or update `functions.php` path to a secure location.

## Windows / XAMPP quick notes
- Use `C:\xampp\php\php.exe` for CLI tests.
- Install Composer and run `composer install` from project root.
- Ensure `curl`, `openssl` enabled in the php.ini used by Apache.
- Put the service-account JSON at `C:\xampp\htdocs\e-commerce-halfa\privite_files\service-acount.json` or set `GOOGLE_APPLICATION_CREDENTIALS`.

## Configuration
- `connect.php`: set your MySQL host, DB name, username and password.
- `functions.php`: contains `$projectId` in the `sendFcmNotification` function — ensure it matches your Firebase `project_id` or let the service-account JSON define the project.




