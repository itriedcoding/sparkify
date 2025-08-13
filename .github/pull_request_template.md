## Summary

This PR introduces or updates Sparkify (advanced PHP framework) and the Sparkify Next.js (TypeScript) frontend, with full dev tooling and Docker support.

## Changes
- Sparkify framework with DI, routing, middleware (errors, CORS, JSON, request ID, request logging), logging, env/config, console, Doctrine DBAL
- API endpoints: `/api/health`, `/api/v1/hello/{name}`
- Next.js 14 app (TypeScript, Tailwind, App Router) proxying `/api/*` to Sparkify
- Dockerfiles and docker-compose for one-command local dev
- PHPUnit setup and a starter test
- Linting/config: EditorConfig, ESLint (web), PHP CS Fixer (php), GitHub Actions CI

## How to test
1. Docker
   - `docker compose up --build`
   - Visit `http://localhost:3000` (frontend) and `http://localhost:8000/api/health` (backend)
2. Local
   - Backend: `cd coreon && composer install && composer run start`
   - Frontend: `cd web && npm install && npm run dev`

## Screenshots
N/A

## Checklist
- [ ] Verified Next.js builds (`npm run build`)
- [ ] Verified API health returns 200
- [ ] Lint/typecheck pass for web
- [ ] Tests pass (`phpunit`)