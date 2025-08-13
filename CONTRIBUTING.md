# Contributing to Coreon

Thanks for your interest in contributing!

## Development setup
- Backend: PHP 8.1+, Composer
- Frontend: Node.js 18+, npm

### Backend
```
cd coreon
composer install
composer run start
```

Run tests:
```
composer test
```

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

## Commit messages
Use Conventional Commits (e.g., `feat:`, `fix:`, `docs:`, `chore:`).

## Pull Requests
- Include tests where possible
- Update docs (`README.md`) if needed
- Keep PRs focused and small

## Code style
- PHP: Follow PSR-12; run a formatter if available
- TypeScript: Use ESLint (next/core-web-vitals) and Prettier

## Reporting issues
Use GitHub Issues. Include steps to reproduce, expected/actual behavior, and environment details.