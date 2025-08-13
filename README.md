# Coreon

An advanced, modular PHP framework paired with a Next.js (TypeScript) frontend.

- Core: DI container (PHP-DI), FastRoute router, middleware pipeline, Symfony HttpFoundation responses, Monolog logging, Whoops error pages (debug), Dotenv config, Doctrine DBAL, Symfony Console, CORS and JSON parsing middleware.
- Web: Next.js 14 App Router, TypeScript, Tailwind, API rewrite proxy to Coreon.

## Quick start with Docker

Requirements: Docker and Docker Compose

```bash
# Build and start services
docker compose up --build

# Web: http://localhost:3000
# API: http://localhost:8000
```

## Manual local setup

- PHP 8.1+ and Composer for Coreon
- Node.js 18+ for the web app

Coreon:
```bash
cd coreon
composer install
composer run start
# http://localhost:8000
```

Web:
```bash
cd web
npm install
npm run dev
# http://localhost:3000 (proxies /api/* to :8000)
```

## Project structure

- `coreon/`: PHP framework source
- `web/`: Next.js frontend

## Useful commands

- `cd coreon && php bin/coreon route:list` — list routes
- `cd web && npm run build` — build frontend

## Configuration

Edit `coreon/.env` and `coreon/config/*.php` as needed.

## License

MIT