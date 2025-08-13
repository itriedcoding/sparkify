# Coreon

Coreon is an advanced, modular PHP framework paired with a Next.js (TypeScript) frontend. It emphasizes performance, developer ergonomics, and modern tooling.

## Features
- PHP 8+ framework with:
  - Dependency Injection (PHP-DI)
  - FastRoute router with controller DI and middleware pipeline
  - Symfony HttpFoundation for requests/responses
  - Monolog logging, Whoops error pages in debug
  - Dotenv configuration, typed env helpers, central Config repository
  - Doctrine DBAL for database access
  - CORS and JSON body parsing middleware
  - Symfony Console CLI (e.g., route:list)
- Next.js 14 App Router frontend with TypeScript and Tailwind
- API gateway: Next.js rewrites `/api/*` to Coreon (`:8000`)
- Dockerized dev environment for one-command startup
- Testing (PHPUnit), EditorConfig, Code of Conduct, Contributing guide, MIT license

## Architecture
- `coreon/` — framework core and app layer
  - `src/Core` — framework internals (kernel, router, middleware, support)
  - `app/Http/Controllers` — application controllers
  - `routes/` — `web.php` and `api.php`
  - `config/` — environment, CORS, services
- `web/` — Next.js app (App Router, TS, Tailwind)

Request lifecycle:
1. HTTP request enters `public/index.php`
2. `Application` bootstraps env, config, container, logger
3. `HttpKernel` runs middleware: ErrorHandling -> CORS -> JSON parser -> Routing
4. `Router` dispatches to controller via DI, `ResponseFactory` normalizes output

## Getting started

### Docker (recommended)
```bash
docker compose up --build
# Web: http://localhost:3000
# API: http://localhost:8000
```

### Local
Backend:
```bash
cd coreon
composer install
composer run start
# http://localhost:8000
```
Frontend:
```bash
cd web
npm install
npm run dev
# http://localhost:3000
```

## Configuration
- Copy and adjust `.env` in `coreon/`
- Key settings: `APP_ENV`, `APP_DEBUG`, `APP_URL`, DB connection or `DATABASE_URL`, CORS origins

## Scripts
Backend:
- `composer start` — PHP built-in server
- `composer console` — CLI (`bin/coreon`)
- `composer test` — PHPUnit

Frontend:
- `npm run dev` — Next.js dev
- `npm run build` — production build
- `npm start` — start built app

## API examples
- `GET /api/health` — health info
- `GET /api/v1/hello/{name}` — greeting

## CI/CD
- Add GitHub Actions workflows to lint and build (see `.github/workflows` folder if added)

## Contributing
See `CONTRIBUTING.md` and `CODE_OF_CONDUCT.md`.

## License
MIT — see `LICENSE`.