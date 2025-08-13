# Sparkify

Sparkify is an advanced, modular PHP framework designed for building high-performance APIs and full-stack applications. It ships with a modern TypeScript/Next.js frontend and a batteries-included developer experience.

Sparkify focuses on: developer ergonomics, explicitness over magic, performance-first routing and middleware, clean architecture, and pragmatic tooling.

## Table of contents
- Overview
- Features
- Architecture
- Request lifecycle
- Directory layout
- Getting started (Docker, Local)
- Configuration and environments
- HTTP: Routing, Controllers, Middleware
- HTTP: Error handling, CORS, JSON, Request IDs, Logging
- DI Container and Services
- Database with Doctrine DBAL
- Logging strategy
- CLI tooling
- Frontend (Next.js)
- API examples
- Security and CORS
- Testing (PHPUnit)
- Linting & formatting
- CI/CD
- Observability & Deployment notes
- Roadmap
- Contributing
- Code of Conduct
- License

## Overview
Sparkify pairs a lightweight PHP 8+ runtime (FastRoute, HttpFoundation, PHP-DI) with a rich middleware pipeline and a fully-typed Next.js 14 frontend. Out-of-the-box, Sparkify gives you a coherent stack for shipping production-grade APIs and web apps.

## Features
- PHP 8+ framework with:
  - Dependency Injection (PHP-DI)
  - FastRoute router with controller DI and middleware pipeline
  - Symfony HttpFoundation for requests/responses
  - Monolog logging with request logging and correlation (X-Request-Id)
  - Whoops pretty error pages in debug
  - Dotenv configuration, typed env helpers, centralized Config repository
  - Doctrine DBAL for database access
  - CORS, JSON body parsing middleware
  - RequestId and RequestLogging middleware
  - Symfony Console CLI (e.g., route:list)
- Next.js 14 App Router (TypeScript + Tailwind)
- Reverse-proxy rewrites from web to API (`/api/*` -> `:8000`)
- Dockerized dev environment for one-command startup
- Testing (PHPUnit), EditorConfig, ESLint, PHP CS Fixer, GitHub Actions CI

## Architecture
- `coreon/` — Sparkify PHP framework + app layer
  - `src/Core` — framework internals (kernel, router, middleware, support)
  - `app/Http/Controllers` — application controllers
  - `routes/` — define `web.php` and `api.php`
  - `config/` — environment, CORS, services
- `web/` — Next.js app (App Router, TS, Tailwind)

## Request lifecycle
1. HTTP request hits `public/index.php`
2. `Application` bootstraps env, config, container, logger
3. `HttpKernel` applies middleware in order:
   - ErrorHandling -> RequestId -> RequestLogging -> CORS -> JSON Body -> Routing
4. `Router` resolves and invokes controller via DI
5. `ResponseFactory` normalizes controller return into an HTTP response

## Directory layout
- `coreon/src/Core` — framework (do not couple app to internals)
- `coreon/app` — your app layer (controllers, services, domain)
- `coreon/routes` — declarative route definitions
- `web/app` — Next.js routes and pages

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

## Configuration and environments
- Edit `coreon/.env`
- Important keys: `APP_ENV`, `APP_DEBUG`, `APP_URL`, `DATABASE_URL` or `DB_*`, `CORS_ALLOWED_ORIGINS`
- PHP timezone is set from `config/app.php`

## HTTP: Routing, Controllers, Middleware
- Define routes in `routes/web.php` and `routes/api.php`
- Handlers may be `Controller@method`, `[Controller::class, 'method']`, or closures
- Add middleware by registering in `HttpKernel`

## HTTP: Error handling, CORS, JSON, Request IDs, Logging
- ErrorHandlingMiddleware: Whoops in debug, 500 otherwise
- CorsMiddleware: reads CORS from config
- JsonBodyParserMiddleware: decodes JSON body into `Request::request`
- RequestIdMiddleware: sets `X-Request-Id` on request/response
- RequestLoggingMiddleware: logs method, path, status, duration, request_id

## DI Container and Services
- PHP-DI container built in `Application::buildContainer`
- Put service definitions in `config/container.php`

## Database with Doctrine DBAL
- Configure via `DATABASE_URL` or `DB_*` in `.env`
- Access via type-hinted `Doctrine\DBAL\Connection`

## Logging strategy
- Monolog logs to `storage/logs/sparkify.log`
- Include `request_id` for correlation across services

## CLI tooling
- `php bin/sparkify route:list` — list routes
- Extend by registering new console commands

## Frontend (Next.js)
- TypeScript + Tailwind; App Router in `web/app`
- API requests proxied to `:8000` via `next.config.mjs` rewrites

## API examples
- `GET /api/health` — health info and environment
- `GET /api/v1/hello/{name}` — greeting payload

## Security and CORS
- Restrict `CORS_ALLOWED_ORIGINS` in production
- Add authentication/authorization middleware as needed

## Testing (PHPUnit)
- Run tests: `cd coreon && composer test`
- Example test in `coreon/tests/`

## Linting & formatting
- PHP CS Fixer config at `coreon/.php-cs-fixer.dist.php`
- ESLint in `web/.eslintrc.json`
- EditorConfig and gitattributes at repo root

## CI/CD
- GitHub Actions workflow `.github/workflows/ci.yml` builds web and runs PHP tests

## Observability & Deployment notes
- Include `X-Request-Id` in logs, propagate across services
- Containerize via provided Dockerfiles; configure health checks and logging drivers

## Roadmap
- Authentication middleware and guards
- Schema validation (e.g., JSON Schema) middleware
- Caching layer integrations (Symfony Cache)
- Background jobs and queues
- OpenAPI generation and docs site

## Contributing
See `CONTRIBUTING.md`.

## Code of Conduct
See `CODE_OF_CONDUCT.md`.

## License
MIT — see `LICENSE`.