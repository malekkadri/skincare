# Skin by Noor (Laravel)

Production-ready skincare website and admin panel for Skin by Noor.

## Setup (Local)

1. Install dependencies:
   - `composer install`
   - `npm install && npm run build` (or `npm run dev`)
2. Configure env:
   - `cp .env.example .env`
   - `php artisan key:generate`
   - Configure DB + `APP_URL`
3. Run migrations:
   - `php artisan migrate`
4. Seed:
   - `php artisan db:seed`
5. Link storage:
   - `php artisan storage:link`

## Seeder strategy (Module 10)

- `CoreSeeder`: required operational baseline (admins/settings/permissions/templates).
- `DemoContentSeeder`: demo/service/marketing/sample consultations.
- `DatabaseSeeder` always runs `CoreSeeder` and skips `DemoContentSeeder` in production unless:
  - `SEED_DEMO_DATA_IN_PRODUCTION=true`

## Production deployment checklist

- Set:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://your-domain`
  - `APP_TIMEZONE=Africa/Tunis`
  - `QUEUE_CONNECTION` to non-`sync`
- Build caches:
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`
- Storage:
  - `php artisan storage:link`
- Queue worker:
  - `php artisan queue:work --tries=3 --timeout=90`
- Scheduler cron:
  - `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

## Ops & launch readiness

- Admin health dashboard: `/admin/ops/health`
- Admin launch readiness page: `/admin/ops/launch-readiness`
- Health endpoint: `/health`
- Sitemap: `/sitemap.xml`
- Backup command: `php artisan ops:backup --prefix=manual`

## Smoke test command

Run:

- `php artisan skinbynoor:smoke-test`

This verifies critical routes, writable directories, base settings, service/category presence, storage link, and queue/scheduler hints.

## Recommended post-deploy checks

1. Login to admin and open **Ops → Launch Readiness**.
2. Confirm at least one active service/category and logo are configured.
3. Verify booking flow end-to-end with a real test appointment.
4. Verify consultation submit flow with AI both enabled and disabled.
5. Run smoke test command and review warnings.
6. Confirm queue worker and scheduler are running.
