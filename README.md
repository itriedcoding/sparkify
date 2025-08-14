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