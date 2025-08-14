# Contributing to Sparkify

Thanks for your interest in contributing!

## Quickstart checklist
- Fork and clone the repo
- Create a feature branch from `main`
- Copy `sparkify/.env.example` to `sparkify/.env` and set secrets
- Run backend and frontend locally; ensure `/api/health` is OK
- Write tests and docs with your change

## Development setup
- Backend: PHP 8.1+, Composer
- Frontend: Node.js 18+, npm

### Backend
```
cd sparkify
composer install
composer run start
```

Run tests and static analysis:
```
composer test
composer stan
```

JWT setup (dev):
- Copy `sparkify/.env.example` to `sparkify/.env` and set `JWT_SECRET`

Scaffolding:
```
php bin/sparkify make:controller FooController
```

Views:
- Add Twig templates under `sparkify/resources/views`
- Configure via `sparkify/config/view.php`

Validation:
- Create `FormRequest` subclasses using Respect/Validation rules

Static analysis and style:
- Run PHP CS Fixer if installed locally (`php-cs-fixer fix`)
- Consider adding phpstan.neon and increasing level over time

### Frontend
```
cd web
npm install
npm run dev
```

Build:
```
npm run build
```

Format/Lint:
```
npx prettier -w .
npm run lint
```

## Commit messages
Use Conventional Commits (e.g., `feat:`, `fix:`, `docs:`, `chore:`, `refactor:`).

## Pull Requests
- Include tests where possible
- Update docs (`README.md`) if needed
- Keep PRs focused and small
- Ensure CI passes (PHP unit tests, web build)

## Code style
- PHP: Follow PSR-12; prefer explicit types and early returns
- TypeScript: Use ESLint (next/core-web-vitals) and Prettier; favor typed APIs

## Reporting issues
Use GitHub Issues. Include steps to reproduce, expected/actual behavior, and environment details.

## Security
Report vulnerabilities privately via `security@your-domain.example` (see `SECURITY.md`).