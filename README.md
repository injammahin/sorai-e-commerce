# SARAI Commerce — Laravel 9

A production-oriented Laravel 9 + MySQL commerce application converted from the supplied SARAI React storefront. The Blade storefront preserves the original editorial design, imagery and interactions while making products, collections, content, merchandising and operations manageable from the administration panel.

## Included

- Dynamic catalogue: top-level and nested categories, products, six-image galleries, colour/size options, stock, pricing, sale pricing, collections and search/filter/sort.
- Commerce: server-side session cart, coupon validation, delivery/tax calculation, guest checkout, transactional stock locking, order history, customer account, wishlist and verified-purchase review workflow.
- Payments: cash on delivery, manual bank/mobile payment and optional SSLCOMMERZ hosted checkout.
- Authentication: registration, secure password reset, Laravel email verification and Google OAuth via Socialite.
- Email: Brevo SMTP-ready order confirmation, admin order alert, verification and reset emails; queue-ready mailables.
- Admin: dashboard, orders, products, categories, collections, customers, hero banners, journal, pages, coupons, reviews, contact messages, newsletter subscribers/CSV export and grouped settings.
- SEO: editable per-product/category/page/post metadata, canonical URLs, semantic content, Open Graph/Twitter tags, Product and Organization JSON-LD, XML sitemap, robots.txt, clean slugs, meaningful image alt text and mobile-first layouts.
- Security: CSRF, route throttling, admin middleware, authorization checks, strong password policy, output escaping, restricted HTML sanitization, MIME/size validation, maximum six product images, soft-deleted products and server-side order totals.

## Requirements

- PHP 8.0.2–8.2 (Laravel 9 support range)
- Composer 2
- MySQL 8 / MariaDB 10.3+
- Node.js 18+
- PHP extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, PDO MySQL, Tokenizer, XML

## Installation

```bash
unzip sarai-laravel9-commerce.zip
cd sarai-laravel
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Create a MySQL database and update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`, then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
php artisan optimize
```

Point the domain document root to the project's `public/` directory. Ensure `storage/` and `bootstrap/cache/` are writable by the web server.

For local development:

```bash
composer install
npm install
npm run dev
php artisan serve
php artisan queue:work
```

## Administrator

- URL: `/login` (after sign-in, administrators are redirected to `/admin`)
- Email: `mahin@gmail.com`
- Initial password: `Mahin@5507`

These defaults come from `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env`. Change the password immediately after first production deployment. Never commit the production `.env` file.

## Brevo SMTP

Create a Brevo transactional SMTP key and set:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-smtp-login
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@your-domain.com
MAIL_FROM_NAME="SARAI"
QUEUE_CONNECTION=database
```

Run `php artisan queue:work --tries=3` under Supervisor/systemd in production. Verify the sender/domain in Brevo and configure SPF, DKIM and DMARC.

## Google login

Create an OAuth 2.0 Web application in Google Cloud and add this exact authorized redirect URL:

`https://your-domain.com/auth/google/callback`

Then set `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, and `GOOGLE_REDIRECT_URI` in `.env`.

## SSLCOMMERZ (optional)

Set `SSLCOMMERZ_ENABLED=true`, the sandbox/live flag, store ID and store password. The application uses the hosted gateway and validates successful transactions server-to-server before marking an order paid.

## Production checklist

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
SESSION_SECURE_COOKIE=true
LOG_LEVEL=warning
```

Also configure HTTPS, a queue worker, daily database/media backups, cron (`* * * * * php artisan schedule:run`), monitoring, mail DNS, Google Search Console/Bing Webmaster Tools, and payment gateway credentials. In the admin panel, update contact/social settings, metadata, shipping threshold/charge, email recipient, pages, banners and store locations.

## Tests

```bash
php artisan test
```

Feature tests cover admin isolation, server-owned pricing, stock enforcement and atomic order creation. The Vite production bundle is committed in `public/build` for deployment convenience, but should be rebuilt whenever CSS or JavaScript changes.

## Important implementation notes

- Uploaded product files go to `storage/app/public/products`; the database stores `storage/...` public paths.
- Existing supplied demonstration artwork is stored in `public/images` and seeded into the database.
- Do not change totals in the browser: checkout always recalculates product price, coupon, shipping and tax on the server.
- Keep `APP_KEY` stable after launch because Laravel uses it for cookies and encrypted values.
