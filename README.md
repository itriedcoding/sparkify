# Sparkify

Sparkify is an advanced, modular PHP framework for building high-performance APIs and full-stack applications. It ships with a modern TypeScript/Next.js frontend and a batteries-included developer experience.

Sparkify focuses on developer ergonomics, explicitness over magic, performance-first routing and middleware, clean architecture, and pragmatic tooling.

---

## Table of Contents
- [Overview](#overview)
- [Key Features](#key-features)
- [Architecture](#architecture)
- [Request Lifecycle](#request-lifecycle)
- [Directory Layout](#directory-layout)
- [Quick Start](#quick-start)
  - [Docker](#docker)
  - [Local](#local)
- [Configuration](#configuration)
  - [Environment Variables](#environment-variables)
  - [CORS](#cors)
- [HTTP](#http)
  - [Routing](#routing)
  - [Controllers](#controllers)
  - [Middleware](#middleware)
  - [Error Handling](#error-handling)
  - [JSON Body Parsing](#json-body-parsing)
  - [Request Correlation and Logging](#request-correlation-and-logging)
  - [Rate Limiting](#rate-limiting)
- [Dependency Injection](#dependency-injection)
- [Database (Doctrine DBAL)](#database-doctrine-dbal)
- [Caching](#caching)
- [Logging](#logging)
- [CLI Tooling](#cli-tooling)
- [Frontend (Next.js)](#frontend-nextjs)
- [API Examples](#api-examples)
- [Security](#security)
- [Testing](#testing)
- [Linting & Formatting](#linting--formatting)
- [CI/CD](#cicd)
- [Performance & Tuning](#performance--tuning)
- [Observability & Deployment](#observability--deployment)
- [FAQ](#faq)
- [Roadmap](#roadmap)
- [Contributing](#contributing)
- [Code of Conduct](#code-of-conduct)
- [License](#license)

---

## Overview
Sparkify pairs a lightweight PHP 8+ runtime (FastRoute, HttpFoundation, PHP-DI) with a rich middleware pipeline and a fully-typed Next.js frontend. Out of the box, Sparkify gives you a coherent stack for shipping production-grade APIs and web apps.

## Key Features
- PHP 8+ framework with:
  - Dependency Injection (PHP-DI)
  - FastRoute router with controller DI and middleware pipeline
  - Symfony HttpFoundation for requests/responses
  - Monolog logging with request logging and correlation (X-Request-Id)
  - Whoops pretty error pages in debug
  - Dotenv and centralized Config repository
  - Doctrine DBAL for database access
  - CORS and JSON body parsing middleware
  - RequestId, RequestLogging, and RateLimit middleware
  - Symfony Console CLI (e.g., route:list)
- Next.js 14 App Router (TypeScript + Tailwind)
- Reverse-proxy rewrites from web to API (`/api/*` -> `:8000`)
- Dockerized dev environment for one-command startup
- Testing (PHPUnit), EditorConfig, ESLint, PHP CS Fixer, GitHub Actions CI
- .env.example, Makefile, Prettier, Security Policy, Issue templates

## Architecture
- `sparkify/` — Sparkify PHP framework + app layer
  - `src/Core` — framework internals (kernel, router, middleware, support)
  - `app/Http/Controllers` — application controllers
  - `routes/` — define `web.php` and `api.php`
  - `config/` — environment, CORS, services
- `web/` — Next.js app (App Router, TS, Tailwind)

### Diagram
```
Client -> Next.js (web) -> rewrites /api/* -> Sparkify (sparkify)
                                  
Sparkify HttpKernel: ErrorHandling -> RequestId -> RateLimit -> RequestLogging -> CORS -> JSON -> Routing -> Controller
```

## Request Lifecycle
1. HTTP request hits `public/index.php`.
2. `Application` bootstraps env, config, container, logger.
3. `HttpKernel` applies middleware in order:
   - ErrorHandling -> RequestId -> RateLimit -> RequestLogging -> CORS -> JSON Body -> Routing
4. `Router` resolves and invokes controller via DI.
5. `ResponseFactory` normalizes controller return into an HTTP response.

## Directory Layout
- `sparkify/src/Core` — framework (do not couple app directly to internals)
- `sparkify/app` — your app layer (controllers, services, domain)
- `sparkify/routes` — declarative route definitions
- `web/app` — Next.js routes and pages

## Quick Start
### Docker
```bash
docker compose up --build
# Web: http://localhost:3000
# API: http://localhost:8000
```

### Local
Backend:
```bash
cd sparkify
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
Sparkify reads environment variables from `sparkify/.env` and structured config from `sparkify/config/*.php`.

- PHP timezone is set via `config/app.php`.
- CORS is configured via `config/app.php['cors']`.
- Copy `sparkify/.env.example` to `sparkify/.env` and adjust.

### Environment Variables
Common keys:
- `APP_NAME` — default: Sparkify
- `APP_ENV` — default: local
- `APP_DEBUG` — default: true
- `APP_TIMEZONE` — default: UTC
- `APP_URL` — default: http://localhost:8000
- `DATABASE_URL` — optional DSN (e.g., mysql://user:pass@127.0.0.1:3306/db)
- or `DB_DRIVER`, `DB_PATH` for SQLite
- `CORS_ALLOWED_ORIGINS` — comma-separated allowed origins (e.g., `http://localhost:3000,https://example.com`)

### CORS
Configured in `config/app.php` under `cors`:
- `allowed_origins`, `allowed_methods`, `allowed_headers`, `exposed_headers`, `allow_credentials`, `max_age`.

## HTTP
### Routing
Define routes in `sparkify/routes/api.php` and `sparkify/routes/web.php`:
```php
$router->get('/api/health', [\App\Http\Controllers\HealthController::class, 'index'], 'api.health');
$router->get('/api/v1/hello/{name}', [\App\Http\Controllers\HelloController::class, 'greet'], 'api.v1.hello');
```
Handler styles supported:
- `"Controller@method"`
- `[Controller::class, 'method']`
- `function (Request $r) { ... }`

### Controllers
Return values are normalized by `ResponseFactory`:
- `Symfony\Component\HttpFoundation\Response` returns as-is
- `array|object` -> `JsonResponse`
- `string` -> `Response`
- `null` -> `204 No Content`

### Middleware
Registered in `HttpKernel` order-sensitive pipeline:
- `ErrorHandlingMiddleware`
- `RequestIdMiddleware`
- `RateLimitMiddleware`
- `RequestLoggingMiddleware`
- `CorsMiddleware`
- `JsonBodyParserMiddleware`
- `RoutingMiddleware`

### Error Handling
- In debug (`app.debug = true`): Whoops pretty pages
- In production: generic `500 Internal Server Error`

### JSON Body Parsing
If `Content-Type: application/json` and a non-empty body, the JSON is decoded into `$request->request`.

### Request Correlation and Logging
- `X-Request-Id` header is generated if absent and echoed on responses.
- Structured log entry per request: `method`, `path`, `status`, `duration_ms`, `request_id`.

### Rate Limiting
- Token-bucket style limiter via `RateLimitMiddleware(limit=120, window=60)`.
- Returns `429 Too Many Requests` with standard `Retry-After` and `X-RateLimit-*` headers.

## Dependency Injection
- Container built in `Application::buildContainer()`.
- Extend bindings in `sparkify/config/container.php`.
- Controllers and handlers can type-hint services; the container will provide them.

## Database (Doctrine DBAL)
- Configure via `DATABASE_URL` or `DB_*`.
- Access a `Doctrine\DBAL\Connection` via DI.

## Caching
- PSR-16 `CacheInterface` binding backed by Symfony ArrayAdapter for easy injection and future swap.

## Logging
- Monolog logs to `sparkify/storage/logs/sparkify.log`.
- Use `Logger` via DI for structured logs.

## CLI Tooling
- `php bin/sparkify route:list` — list all registered routes.
- Makefile included for common tasks: `make up`, `make down`, `make phpunit`, `make build`.

## Frontend (Next.js)
- TypeScript + Tailwind, App Router in `web/app`.
- `next.config.mjs` proxies `/api/*` to `http://localhost:8000/api/*`.
- Prettier config added at `web/.prettierrc`.

## API Examples
- `GET /api/health` — health info and environment
- `GET /api/v1/hello/{name}` — greeting payload

## Security
- Restrict `CORS_ALLOWED_ORIGINS` in production.
- Terminate TLS at a reverse proxy (e.g., nginx/Traefik) in production.
- SECURITY policy in `SECURITY.md`.

## Testing
- `cd sparkify && composer test`
- Add tests in `sparkify/tests/` (PHPUnit 11.x).

## Linting & Formatting
- PHP CS Fixer config at `sparkify/.php-cs-fixer.dist.php` (PSR-12, short arrays, etc.)
- ESLint config at `web/.eslintrc.json` (next/core-web-vitals)
- Prettier config at `web/.prettierrc`
- EditorConfig and `.gitattributes` at repo root

## CI/CD
- GitHub Actions workflow `.github/workflows/ci.yml`:
  - PHP job: install deps + run PHPUnit
  - Web job: install deps + typecheck + lint + build

## Performance & Tuning
- Use PHP 8.3+ for JIT and performance baseline.
- Keep middleware lean and order-sensitive (short-circuit early when possible).
- Prefer JSON responses and avoid heavy templating on the server.
- Enable opcache in production; consider a real web server (nginx) proxying to PHP-FPM.

## Observability & Deployment
- Correlate logs with `X-Request-Id` throughout downstream services.
- Add health checks (`/api/health`) to orchestrators and load balancers.
- Consider centralizing logs (ELK, Loki) and metrics (Prometheus).

## FAQ
- Q: Can I use ORM migrations?
  A: Sparkify ships with DBAL; you can integrate Doctrine Migrations or any tool of choice.
- Q: How do I add authentication?
  A: Implement an auth middleware and register it before routing.

## Roadmap
- Authentication middleware and guards
- Request/response schema validation
- Caching integrations (Symfony Cache, Redis)
- Background jobs and queues
- OpenAPI generation and docs site

## Contributing
See [`CONTRIBUTING.md`](CONTRIBUTING.md).

## Code of Conduct
See [`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md).

## License
MIT — see [`LICENSE`](LICENSE).