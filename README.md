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

## Usage
- Use the provided endpoints under folders like `products/`, `cart/`, `order/`, `auth/` etc.
- To test FCM quickly, open in browser (or run CLI):
  `http://your-host/e-commerce-halfa/test.php` or
  `C:\xampp\php\php.exe C:\xampp\htdocs\e-commerce-halfa\test.php`

## Troubleshooting
- "FCM Error: service account JSON not found": place the JSON where the code checks or set `GOOGLE_APPLICATION_CREDENTIALS`.
- TLS/curl errors: ensure `ca-certificates` (Linux) or `cacert.pem` (Windows) is configured and `curl.cainfo`/`openssl.cafile` set in php.ini.
- Missing vendor folder: run `composer install`.
- Time skew errors: sync server time (NTP).
- Check logs: Apache/Nginx error logs and PHP error log for `FCM Error` or `FCM Guzzle Error` messages.

## Testing
- Unit tests: none included; use `test.php` to test FCM and endpoint calls.
- Manual API tests: use Postman or curl to call endpoints and verify JSON responses.

## Contributing
- Keep code small and focused. Follow existing coding style. Fix bugs at the root cause and document changes.

## Security notes
- Never commit your service-account JSON to source control. Store it outside webroot and restrict file permissions.
- Rotate keys if they are exposed.

## License
Include your preferred license here (e.g., MIT) or add a `LICENSE` file.

---
If you want, I can also add a short `CONTRIBUTING.md`, update `test.php` to print more verbose FCM errors, or add example curl requests for the main endpoints.
