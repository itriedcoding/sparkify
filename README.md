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
... (unchanged content omitted for brevity)

## Quick Start
### Docker
... (unchanged)

### Local
... (unchanged)

### Checklist
- Copy `sparkify/.env.example` to `sparkify/.env` and set secrets (e.g., `JWT_SECRET`)
- Start backend and frontend; confirm `/api/health` and web are reachable
- Run `php bin/sparkify route:list` and `npm run build` to verify toolchains
- Add a controller via `php bin/sparkify make:controller FooController`

## Configuration
... (unchanged with JWT/Sessions/Views noted)

## HTTP
... (unchanged)

## Templating (Twig)
... (unchanged)

## Dependency Injection
... (unchanged)

## Database (Doctrine DBAL)
... (unchanged)

## Caching
... (unchanged)

## Logging
... (unchanged)

## CLI Tooling
... (unchanged)

## URL Generation
... (unchanged)

## HTTP Clients
... (unchanged)

## Frontend (Next.js)
... (unchanged)

## API Examples
... (unchanged)

## How to Use
... (unchanged)

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
... (unchanged)

## Testing
... (unchanged)

## Linting & Formatting
... (unchanged; add `composer stan` for static analysis)

## CI/CD
... (unchanged)

## Performance & Tuning
... (unchanged)

## Observability & Deployment
... (unchanged)

## FAQ
... (unchanged)

## Roadmap
... (unchanged)

## Contributing
See [`CONTRIBUTING.md`](CONTRIBUTING.md).

## Code of Conduct
See [`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md).

## License
MIT — see [`LICENSE`](LICENSE).