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
  - [JWT](#jwt)
- [HTTP](#http)
  - [Routing](#routing)
  - [Controllers](#controllers)
  - [Middleware](#middleware)
  - [Error Handling](#error-handling)
  - [JSON Body Parsing](#json-body-parsing)
  - [Request Correlation and Logging](#request-correlation-and-logging)
  - [Rate Limiting](#rate-limiting)
  - [ETag and Compression](#etag-and-compression)
  - [Validation (FormRequest)](#validation-formrequest)
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
  - RequestId, RateLimit, RequestLogging, ETag, and Compression middleware
  - JWT authentication middleware and config
  - FormRequest validation (Respect/Validation)
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
  - `config/` — environment, CORS, services, auth (JWT)
- `web/` — Next.js app (App Router, TS, Tailwind)

### Diagram
```
Client -> Next.js (web) -> rewrites /api/* -> Sparkify (sparkify)
                                  
Sparkify HttpKernel: ErrorHandling -> RequestId -> RateLimit -> RequestLogging -> CORS -> JSON -> Routing -> ETag -> Compression -> Response
```

## Request Lifecycle
1. HTTP request hits `public/index.php`.
2. `Application` bootstraps env, config, container, logger.
3. `HttpKernel` applies middleware in order:
   - ErrorHandling -> RequestId -> RateLimit -> RequestLogging -> CORS -> JSON Body -> Routing -> ETag -> Compression
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
- JWT is configured via `config/auth.php` and `JWT_*` envs.
- Copy `sparkify/.env.example` to `sparkify/.env` and adjust.

### Environment Variables
See `.env.example` for the full set. Highlights:
- `APP_*`, DB `DATABASE_URL` or `DB_*`, `CORS_ALLOWED_ORIGINS`
- `JWT_ISSUER`, `JWT_AUDIENCE`, `JWT_TTL`, `JWT_ALG`, `JWT_SECRET`

### CORS
Configured in `config/app.php` under `cors`.

### JWT
- Configure in `config/auth.php` and `.env`.
- Protect routes by adding `JwtAuthMiddleware` to the pipeline or implementing route groups.

## HTTP
### Routing
Define routes in `sparkify/routes/api.php` and `sparkify/routes/web.php`.

### Controllers
Return values are normalized by `ResponseFactory`.

### Middleware
Registered in `HttpKernel` order-sensitive pipeline:
- `ErrorHandlingMiddleware`
- `RequestIdMiddleware`
- `RateLimitMiddleware`
- `RequestLoggingMiddleware`
- `CorsMiddleware`
- `JsonBodyParserMiddleware`
- `RoutingMiddleware`
- `ETagMiddleware`
- `CompressionMiddleware`

### Error Handling
Pretty error pages in debug, generic 500 otherwise.

### JSON Body Parsing
If `Content-Type: application/json`, JSON is decoded into `$request->request`.

### Request Correlation and Logging
`X-Request-Id` header and structured logs.

### Rate Limiting
Enforces request limits per IP and method; sends Retry-After and X-RateLimit headers.

### ETag and Compression
Conditional GETs via ETag; gzip compression when accepted by clients.

### Validation (FormRequest)
Create a FormRequest class that returns Respect/Validation rules and call `validate($request)`.

## Dependency Injection
- Container built in `Application::buildContainer()`.
- Extend bindings in `sparkify/config/container.php`.

## Database (Doctrine DBAL)
Configure via env; inject `Doctrine\DBAL\Connection` where needed.

## Caching
PSR-16 `CacheInterface` binding (ArrayAdapter) for easy injection.

## Logging
Monolog writes to `sparkify/storage/logs/sparkify.log`.

## CLI Tooling
`php bin/sparkify route:list` and Makefile targets.

## Frontend (Next.js)
TS + Tailwind; rewrites `/api/*` to Sparkify.

## API Examples
- `GET /api/health` — health checks
- `GET /api/metrics` — uptime/memory
- `GET /api/v1/hello/{name}` — greeting

## Security
- Set strong `JWT_SECRET` in production
- Restrict CORS; run behind TLS

## Testing
Run `cd sparkify && composer test`.

## Linting & Formatting
PHP CS Fixer, ESLint, Prettier, EditorConfig.

## CI/CD
GitHub Actions builds and tests web and PHP.

## Performance & Tuning
Use PHP 8.3+, enable opcache, keep middleware lean.

## Observability & Deployment
Correlated logs, health checks, central logging/metrics systems.

## FAQ
See common Q&A in prior section.

## Roadmap
Auth guards, schema validation, caching (Redis), queues, OpenAPI.

## Contributing
See [`CONTRIBUTING.md`](CONTRIBUTING.md).

## Code of Conduct
See [`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md).

## License
MIT — see [`LICENSE`](LICENSE).