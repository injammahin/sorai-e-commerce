# AATCHALA launch checklist

- [ ] Production domain points to `public/`, HTTPS redirects are active, and `APP_URL` is correct.
- [ ] `APP_ENV=production`, `APP_DEBUG=false`, and a unique `APP_KEY` are set.
- [ ] MySQL credentials use a dedicated least-privilege database user.
- [ ] `php artisan migrate --force`, `storage:link`, `optimize`, and `queue:restart` complete.
- [ ] Admin default password is replaced and only authorized administrators remain.
- [ ] Brevo sender/domain, SPF, DKIM and DMARC pass; verification, reset and order emails arrive.
- [ ] Google OAuth callback exactly matches the production callback URL.
- [ ] Payment sandbox tests cover success, failure and cancellation before switching to live credentials.
- [ ] Shipping, tax, free-delivery threshold, contact information and social links are reviewed in Settings.
- [ ] Terms, privacy, returns and shipping text have legal/business approval.
- [ ] Product prices, stock, size/colour choices, images and alt text are reviewed.
- [ ] Sitemap is submitted to Search Console and Bing Webmaster Tools; robots.txt is reachable.
- [ ] Database and `storage/app/public` backups are automated and restoration is tested.
- [ ] Queue worker, scheduler, uptime monitoring, error alerts and log rotation are running.
- [ ] Checkout is tested as guest, registered email user and Google user on mobile and desktop.
