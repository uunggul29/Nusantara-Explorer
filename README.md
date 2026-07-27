# Nusantara Explorer

**Nusantara Explorer** is a digital cultural tour guide for Indonesia's youth — a "Cultural Expedition for Young People." It lets visitors explore Indonesia's 38 provinces through an interactive map, learning the history, capital, traditional houses, food, ceremonies, and arts of each region, then test what they've learned with an embedded quiz per province.

The project started as a static HTML/Bootstrap/jQuery site and was rebuilt into a full **Laravel + Inertia.js + Vue 3** application with a database-backed **admin panel**, so non-technical team members can update province content, team members, and homepage stats without touching code.

## Features

- **Interactive map** (Leaflet + OpenStreetMap) with a marker for every one of the 38 provinces, linking straight to that province's page.
- **Province pages** — capital, history, and a filterable culture gallery (Artifact / Food / Tradition / Arts), plus an embedded mini-quiz per province.
- **Home page** — an auto-rotating hero carousel, animated stat counters, and a rotating team/testimonial section.
- **Admin panel** (`/admin`) — CRUD management for:
  - Provinces (profile, hero image, description, history article, map coordinates)
  - Culture items per province (category, title, image)
  - Team members
  - Home page stats
- Authentication via [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) (Inertia + Vue stack).

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 (PHP) |
| Frontend | Vue 3 + Inertia.js |
| Bundler | Vite |
| Database | MySQL |
| Map | Leaflet.js + OpenStreetMap |
| Admin UI | Tailwind CSS |
| Public site UI | Custom CSS (Bootstrap-based legacy pages + a bespoke design system for Home/Islands/Province pages) |

## Getting Started

### Requirements

- PHP >= 8.2
- Composer
- Node.js + npm
- MySQL

### Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```
DB_CONNECTION=mysql
DB_DATABASE=nusantara_explorer
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 3. Run migrations and seed initial data (team members, home stats, admin user)
php artisan migrate --seed

# 4. Link public storage (for uploaded images)
php artisan storage:link

# 5. Build frontend assets
npm run build      # production build
# or
npm run dev        # development, with hot reload
```

Then visit the app in your browser (e.g. via Laragon at `http://nusantara-explorer.test`, or `php artisan serve`).

### Default Admin Login

```
Email:    admin@nusantaraexplorer.test
Password: password
```

**Change this password immediately after your first login.**

## Project Structure

- `app/Http/Controllers` — public controllers (`HomeController`, `IslandController`, `ProvinceController`) and admin controllers under `Admin/`.
- `app/Models` — `Province`, `CultureItem`, `TeamMember`, `HomeStat`.
- `app/Console/Commands/MigrateProvinceContent.php` — one-off importer used to migrate the original static province pages into the database.
- `resources/js/Pages` — Inertia pages: `Home.vue`, `Islands.vue`, `ProvinceShow.vue`, and the `Admin/` CRUD screens.
- `storage/app/public/provinces/{slug}` — per-province images (hero, history, culture gallery).

## Credits

Built by students and faculty of the Information Systems Study Program, Faculty of Science and Technology, **Pignatelli Triputra University**:

- Maria Atik Sunarti Ekowati, ST., M.Kom — Senior Lecturer
- Unggul Widya Saputra — Leader
- Gabriel Silvano
- Evan Valentino
- Zadrak Yaboisembut
- Benedikta Cindy Permatasari

## License

This project is open-sourced software.
