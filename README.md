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
  - [Checklist](#checklist)
- [Configuration](#configuration)
  - [Environment Variables](#environment-variables)
  - [CORS](#cors)
  - [JWT](#jwt)
  - [Sessions](#sessions)
  - [Views](#views)
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
  - [Sessions and CSRF](#sessions-and-csrf)
- [Templating (Twig)](#templating-twig)
- [Dependency Injection](#dependency-injection)
- [Database (Doctrine DBAL)](#database-doctrine-dbal)
- [Caching](#caching)
- [Logging](#logging)
- [CLI Tooling](#cli-tooling)
  - [Scaffolding Generators](#scaffolding-generators)
- [URL Generation](#url-generation)
- [HTTP Clients](#http-clients)
- [Frontend (Next.js)](#frontend-nextjs)
- [API Examples](#api-examples)
- [How to Use](#how-to-use)
  - [Build a Feature (Example)](#build-a-feature-example)
- [Advanced Topics](#advanced-topics)
  - [Grouping and route-specific middleware](#grouping-and-route-specific-middleware)
  - [Custom error pages](#custom-error-pages)
  - [Switching cache backends](#switching-cache-backends)
- [Troubleshooting](#troubleshooting)
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
- Full middleware stack: ErrorHandling, Session, CSRF, RequestId, RateLimit, RequestLogging, CORS, JSON Body, Routing, ETag, Compression
- JWT authentication and FormRequest validation
- Twig templating and BaseController helpers
- DI (PHP-DI), DB (Doctrine DBAL), Cache (PSR-16), Console (Symfony)
- Next.js 14 frontend with TypeScript and Tailwind

## Architecture
- `sparkify/` — Sparkify PHP framework + app layer
  - `src/Core` — framework internals (kernel, router, middleware, support)
  - `app/Http/Controllers` — application controllers
  - `routes/` — `web.php` and `api.php`
  - `config/` — env, app, auth (JWT), view, session, container
- `web/` — Next.js app (App Router, TS, Tailwind)

### Diagram
```
Client -> Next.js (web) -> rewrites /api/* -> Sparkify (sparkify)
                                  
Sparkify HttpKernel: ErrorHandling -> Session -> CSRF -> RequestId -> RateLimit -> RequestLogging -> CORS -> JSON -> Routing -> ETag -> Compression -> Response
```

## Request Lifecycle
1. Request enters `public/index.php`.
2. `Application` loads env and config, builds container, registers logger.
3. `HttpKernel` executes middleware in order and dispatches to a controller.
4. `ResponseFactory` normalizes controller return into an HTTP response.

## Directory Layout
- `sparkify/src/Core` — framework internals
- `sparkify/app` — your controllers and domain code
- `sparkify/routes` — route definitions
- `sparkify/resources/views` — Twig templates
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

### Checklist
- Copy `sparkify/.env.example` to `sparkify/.env`; set `JWT_SECRET`
- Verify `http://localhost:8000/api/health` returns status ok
- Open web at `http://localhost:3000` and confirm it proxies to the API
- List routes: `php bin/sparkify route:list`

## Configuration
Configuration is PHP arrays in `sparkify/config/*.php` read at boot.
- `config/app.php` — app name, env, debug, timezone, CORS
- `config/auth.php` — JWT settings (issuer, audience, ttl, alg, secret)
- `config/session.php` — session cookie name/flags and lifetime
- `config/view.php` — Twig template paths and cache directory
- `config/container.php` — DI bindings (DB, logger, cache, view manager)

### Environment Variables
Set in `.env` and read by `vlucas/phpdotenv`.
- App: `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_TIMEZONE`, `APP_URL`
- DB: `DATABASE_URL` or `DB_DRIVER`, `DB_PATH`
- CORS: `CORS_ALLOWED_ORIGINS`
- JWT: `JWT_ISSUER`, `JWT_AUDIENCE`, `JWT_TTL`, `JWT_ALG`, `JWT_SECRET`

### CORS
Configured under `app.cors` (allowed origins/methods/headers, credentials, max-age).

### JWT
Configure `config/auth.php` and set `JWT_*` env vars. Add `JwtAuthMiddleware` to protect endpoints.

### Sessions
`SessionMiddleware` enables cookie-based sessions using Symfony Session. Cookies are secure/httponly with configurable SameSite.

### Views
`ViewManager` renders Twig templates from `resources/views`. Cache can be enabled via `VIEW_CACHE`.

## HTTP
### Routing
Define routes in `sparkify/routes/api.php` and `sparkify/routes/web.php`.
Handlers can be `Controller@method`, `[Controller::class, 'method']`, or closures.

### Controllers
Return `Response`, array/object (JSON), string (HTML/text), or `null` (204). Use `BaseController` for helpers.

### Middleware
Order-sensitive pipeline registered in `HttpKernel`. Add your own by pushing callables.

### Error Handling
Whoops pretty pages in debug; generic 500 otherwise.

### JSON Body Parsing
`JsonBodyParserMiddleware` decodes `application/json` bodies into `$request->request`.

### Request Correlation and Logging
`RequestIdMiddleware` adds `X-Request-Id`. `RequestLoggingMiddleware` logs method/path/status/duration.

### Rate Limiting
`RateLimitMiddleware(limit=120, window=60)` with `Retry-After` and `X-RateLimit-*` headers.

### ETag and Compression
`ETagMiddleware` supports conditional requests; `CompressionMiddleware` gzips responses when accepted.

### Validation (FormRequest)
Extend `FormRequest`, define `rules()`, and call `validate($request)` in controllers.

### Sessions and CSRF
`SessionMiddleware` starts sessions. `CsrfMiddleware` verifies `X-CSRF-Token` for state-changing requests.

## Templating (Twig)
- Configure via `config/view.php`
- Render: `ViewManager::render('home.html.twig', ['title' => 'Home'])`
- Use `BaseController::view($views, 'template', $data)` for convenience

## Dependency Injection
PHP-DI container built in `Application::buildContainer()`. Bind services in `config/container.php`. Controllers can type-hint services in constructors.

## Database (Doctrine DBAL)
Configure via `DATABASE_URL` or `DB_*`. Inject `Doctrine\DBAL\Connection` into constructors and use `fetchAllAssociative`, `executeQuery`, etc.

## Caching
PSR-16 cache via `CacheInterface` bound to Symfony Cache (ArrayAdapter by default). Swap to `FilesystemAdapter` or Redis easily.

## Logging
Monolog logs to `sparkify/storage/logs/sparkify.log`. Inject `Psr\Log\LoggerInterface` to log structured data.

## CLI Tooling
- `php bin/sparkify route:list` — list routes
- `php bin/sparkify make:controller FooController` — scaffold controller
- Makefile targets: `make up`, `make down`, `make phpunit`, `make build`

### Scaffolding Generators
Use `make:controller` to bootstrap a controller with an `index` action.

## URL Generation
`Sparkify\Core\Routing\UrlGenerator` builds URLs from route names and params.
```php
$gen = new Sparkify\Core\Routing\UrlGenerator($router->list());
$path = $gen->route('api.v1.articles.show', ['id' => 42]); // /api/v1/articles/42
```

## HTTP Clients
Use Symfony HttpClient or Guzzle for integrations.
```php
$client = Symfony\Component\HttpClient\HttpClient::create();
$response = $client->request('GET', 'https://api.example.com');
```
```php
$guzzle = new GuzzleHttp\Client();
$res = $guzzle->get('https://api.example.com');
```

## Frontend (Next.js)
Next.js app in `web/` proxies `/api/*` to `http://localhost:8000/api/*` via `next.config.mjs`. Pages live in `web/app`.

## API Examples
- `GET /api/health` — health info and service checks
- `GET /api/metrics` — uptime and memory usage
- `GET /api/v1/hello/{name}` — greeting

## How to Use
Follow the checklist, then generate a controller, add routes, validate requests, and optionally render views. See the example under [Build a Feature (Example)](#build-a-feature-example).

### Build a Feature (Example)
1) Generate controller
```bash
cd sparkify
php bin/sparkify make:controller ArticlesController
```
2) Implement actions
```php
public function index(): array { return ['articles' => []]; }
public function show(Request $r, string $id): array { return ['id' => $id]; }
```
3) Wire routes
```php
$router->get('/api/v1/articles', [\App\Http\Controllers\ArticlesController::class, 'index'], 'api.v1.articles.index');
$router->get('/api/v1/articles/{id}', [\App\Http\Controllers\ArticlesController::class, 'show'], 'api.v1.articles.show');
```
4) Validate (optional)
```php
use Sparkify\Core\Validation\FormRequest; use Respect\Validation\Validator as v;
class ShowArticleRequest extends FormRequest { public function rules(): array { return ['id' => v::intType()->min(1)]; }}
// (new ShowArticleRequest())->validate($request);
```
5) Render view (optional)
```php
use Sparkify\Core\Http\BaseController; use Sparkify\Core\View\ViewManager;
final class ArticlesController extends BaseController { public function home(ViewManager $views) { return $this->view($views, 'home.html.twig', ['title'=>'Home','heading'=>'Welcome']); }}
```
6) Protect (optional)
- Add `JwtAuthMiddleware` or custom middleware near the top of the pipeline
- For browsers, include `X-CSRF-Token` from the session

---

## Advanced Topics
### Grouping and route-specific middleware
- Implement route groups by extending the router to register arrays of middleware per route before `RoutingMiddleware`.

### Custom error pages
- Swap `PrettyPageHandler` in debug and add a custom production error renderer with a Twig template.

### Switching cache backends
- Replace `ArrayAdapter` with `FilesystemAdapter` or `RedisAdapter` and wrap with `Psr16Cache` in `config/container.php`.

## Troubleshooting
- 500 errors: enable debug in `.env` (`APP_DEBUG=true`)
- CORS issues: verify `CORS_ALLOWED_ORIGINS` and browser console
- JWT unauthorized: check `Authorization: Bearer` header and `JWT_SECRET`
- CSRF 419: include `X-CSRF-Token` from session on state-changing requests

## Security
- Set strong `JWT_SECRET` in production
- Restrict CORS; run behind TLS

## Testing
- `cd sparkify && composer test`
- Add tests in `sparkify/tests/` (PHPUnit 11.x)

## Linting & Formatting
- PHP CS Fixer, ESLint, Prettier, EditorConfig
- Static analysis: `composer stan`

## CI/CD
GitHub Actions builds frontend and runs PHP unit tests on PRs and pushes.

## Performance & Tuning
Use PHP 8.3+, enable opcache, and keep middleware lean. Prefer JSON where possible and avoid blocking I/O in requests.

## Observability & Deployment
Include `X-Request-Id` in logs, expose `/api/health` for orchestrators, and ship logs/metrics to a central system.

## FAQ
- Migrations? Integrate Doctrine Migrations or another tool alongside DBAL.
- Auth? Use `JwtAuthMiddleware` and add guards/role checks in controllers.

## Roadmap
- Auth guards, schema validation, caching (Redis), queues, OpenAPI

## Contributing
See [`CONTRIBUTING.md`](CONTRIBUTING.md).

## Code of Conduct
See [`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md).

## License
MIT — see [`LICENSE`](LICENSE).