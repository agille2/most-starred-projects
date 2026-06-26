# GitHub PHP Projects Viewer

This application displays the most-starred public PHP projects from GitHub and stores them in a local database. The landing page is the project list at `/`, and each project has a detail page with metadata such as stars, creation date, last push date, and repository ID.

## Features

- Landing page shows a list of GitHub PHP projects
- Detail page for each project
- Refresh project data from GitHub using a manual refresh endpoint
- Uses Laravel for backend routing and data persistence
- Uses Vite to compile frontend assets

## Requirements

- PHP 8.1+ (or the version supported by this Laravel project)
- Composer
- Node.js and npm
- A database supported by Laravel (SQLite, MySQL, PostgreSQL, etc.)

## Setup

1. Install PHP dependencies:

```bash
composer install
```

2. Install JavaScript dependencies:

```bash
npm install
```

3. Copy the example environment file and generate an application key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env`.

5. Run migrations:

```bash
php artisan migrate
```

6. Build frontend assets for production:

```bash
npm run build
```

## Development

Start the local Laravel server and Vite dev server for development.

```bash
php artisan serve
npm run dev
```

Then open the app in your browser at the URL shown by `php artisan serve` (usually `http://127.0.0.1:8000`).

If you are using GitHub Codespaces, make sure your Vite server is accessible from the forwarded port and use the HTTPS forwarded URL.

## Routes

- `/` — GitHub project list landing page
- `/github-projects` — Alias for the landing page
- `/github-projects/{project}` — Project detail page
- `/github-projects/refresh` — Refresh project data from GitHub

## Notes

- The front-end uses Vite asset loading via `@vite(['resources/css/app.css', 'resources/js/app.js'])`.

## License

This project is licensed under MIT. See `LICENSE` for details.
