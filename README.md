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

## Key additions (this repo)
- Templating with Twig (`sparkify/resources/views`)
- URL generator for named routes
- Controller generator CLI (`php bin/sparkify make:controller FooController`)
- Notes for sessions/CSRF (see below)
- HTTP client options (Symfony HttpClient, Guzzle)

## Sessions and CSRF
Sparkify can integrate Symfony Session and a CSRF token strategy. Recommended:
- Use `symfony/http-foundation` Session for stateful pages
- Implement a CSRF middleware that issues tokens and validates header `X-CSRF-Token`

## Templating (Twig)
- Configure templates in `sparkify/config/view.php`
- Render via `Sparkify\Core\View\ViewManager`
- Example template: `resources/views/home.html.twig`

## URL Generation
- Use `Sparkify\Core\Routing\UrlGenerator` to build URLs from route names and params.

## HTTP Clients
- Included: `symfony/http-client` and `guzzlehttp/guzzle` for integrations.

For the rest of the sections, see previous content above in this README.

## How to Use
Sparkify is designed to be productive immediately while remaining explicit and modular.

### Build a Feature (Example)
1) Generate a controller
```bash
cd sparkify
php bin/sparkify make:controller ArticlesController
```

2) Implement actions
```php
// sparkify/app/Http/Controllers/ArticlesController.php
public function index(): array { return ['articles' => []]; }
public function show(Request $r, string $id): array { return ['id' => $id]; }
```

3) Define routes
```php
// sparkify/routes/api.php
$router->get('/api/v1/articles', [\App\Http\Controllers\ArticlesController::class, 'index'], 'api.v1.articles.index');
$router->get('/api/v1/articles/{id}', [\App\Http\Controllers\ArticlesController::class, 'show'], 'api.v1.articles.show');
```

4) Validate requests (optional)
```php
// sparkify/app/Http/Requests/ShowArticleRequest.php
use Sparkify\Core\Validation\FormRequest;
use Respect\Validation\Validator as v;
class ShowArticleRequest extends FormRequest { public function rules(): array { return ['id' => v::intType()->min(1)]; }}
// In controller method: (new ShowArticleRequest())->validate($request);
```

5) Render views (optional)
```php
// sparkify/app/Http/Controllers/ArticlesController.php
use Sparkify\Core\Http\BaseController; use Sparkify\Core\View\ViewManager;
final class ArticlesController extends BaseController { public function home(ViewManager $views) { return $this->view($views, 'home.html.twig', ['title'=>'Home','heading'=>'Welcome']); }}
```

6) Secure endpoints (optional)
- Add `JwtAuthMiddleware` to the pipeline for protected routes
- Use CSRF for state-changing requests in browsers (`X-CSRF-Token`)

7) Call from Next.js frontend
- Fetch via `/api/v1/articles` (rewritten to Sparkify)

Notes
- Inject services (DB, Cache, Logger) via type hints in controller constructors
- Prefer returning arrays/objects; they are normalized to JSON automatically