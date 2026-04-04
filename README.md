# Skin by Noor (Laravel)

Production-ready skincare website and admin panel for Skin by Noor.

## Module 9 Readiness Notes

### Environment and production essentials

Set these in `.env` for production:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain`
- `APP_TIMEZONE=Africa/Tunis`
- `QUEUE_CONNECTION` (recommended non-sync driver)
- `DEFAULT_SUPER_ADMIN_EMAIL` (optional, defaults to `admin@skinbynoor.test`)
- WhatsApp keys (`WHATSAPP_*`) and AI keys (`AI_*`) as needed.

### Required operational setup

- Run queue worker (example): `php artisan queue:work --tries=3 --timeout=90`
- Run scheduler (cron): `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`
- Create storage symlink: `php artisan storage:link`

### Permissions and admin users

Roles shipped:

- `super_admin`
- `admin`
- `editor`
- `receptionist`

Seeded super admin email defaults to `admin@skinbynoor.test`.

### SEO, sitemap, and robots

- Dynamic sitemap: `/sitemap.xml`
- Dynamic robots: `/robots.txt`
- Canonical/OG/Twitter meta tags are rendered from page-level SEO defaults and content metadata.

### Backup and health commands

- Manual backup artifact: `php artisan ops:backup --prefix=manual`
- Health probe endpoint: `/health`
- Admin health dashboard: `/admin/ops/health`

> Note: Backup command currently creates a safe placeholder artifact in `storage/app/backups` to provide a clean extension point for managed DB backup tooling.
