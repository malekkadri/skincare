# Asthetika

Asthetika is a Laravel website and admin panel for **Dr Aziz Sahly** (médecine esthétique et soins de la peau), based in La Soukra, Ariana, Tunisie.

## Requirements

- PHP 8.1+
- Composer
- Node.js + npm
- MySQL

## Local setup

1. Install backend dependencies:
   - `composer install`
2. Prepare environment:
   - `cp .env.example .env`
   - `php artisan key:generate`
   - configure database values in `.env`
3. Run database setup:
   - `php artisan migrate --seed`
4. Install and build frontend assets:
   - `npm install`
   - `npm run build`
5. Start the app:
   - `php artisan serve`

## Admin access (local seed default)

- Email: `admin@asthetika.test`
- Password: `password123`

> Change this password before production.

## Booking preselection example

Use service preselection from public links:

- `/booking?service=hydrafacial-essentiel`

## Production reminders

- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Use secure production DB credentials
- Run `npm run build`
- Run `php artisan config:cache route:cache view:cache`
- Change the seeded admin password
- Configure mail/WhatsApp integrations if needed
