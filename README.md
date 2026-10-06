# Portfolio

A Laravel + Livewire + Tailwind portfolio for a software developer.
Public site, admin panel, cached, queued, tested.

## Stack

- **Laravel 12** — framework
- **Livewire 3** — interactive components (no SPA)
- **Tailwind CSS 4** — styling
- **Alpine.js** — tiny interactions (ships with Livewire)
- **Vite** — asset bundling
- **MySQL / PostgreSQL / SQLite** — database
- **Database driver** — cache, queue, sessions (Redis optional)

## Requirements

- PHP 8.3+
- Composer 2
- Node 20+
- MySQL 8 or PostgreSQL 15 (SQLite works for local dev)

## Local setup

```bash
git clone https://github.com/you/portfolio.git
cd portfolio

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configure DB in .env, then:
php artisan migrate --seed
php artisan storage:link

npm run dev
php artisan serve