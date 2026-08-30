# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build & Development Commands

### Install dependencies
```bash
composer install
npm install
```

### Compile frontend assets
```bash
# One-time build
npx gulp

# Watch mode (rebuilds on changes)
npx gulp watch

# Individual tasks
npx gulp style        # Compile frontend CSS
npx gulp style_admin  # Compile admin CSS
npx gulp script       # Bundle frontend JS
npx gulp script_admin # Bundle admin JS
```

### Symfony console
```bash
# Clear cache
bin/console cache:clear

# Database migrations
bin/console doctrine:migrations:migrate

# Install bundle assets (after adding/changing bundle public assets)
bin/console assets:install web
```

### Run tests

```bash
./vendor/bin/.phpunit/phpunit-8.5-0/phpunit -c phpunit.xml.dist tests/Volley/Security/AuthRoutesTest.php
```

PHPUnit 8.5 is bootstrapped by `symfony/phpunit-bridge` into
`vendor/bin/.phpunit/`. Run `simple-phpunit` **exactly once**, on a fresh
checkout, to download it:

```bash
./vendor/bin/simple-phpunit --version || (cd vendor/bin/.phpunit/phpunit-8.5-0 && composer update --no-dev)
```

`simple-phpunit` is expected to fail its own final `composer install` step
against a stale lock file; the `composer update --no-dev` fallback finishes the
install. From then on **always invoke the downloaded binary directly**, as in
the command above — re-running `simple-phpunit` rewrites that directory's
`composer.json` and breaks the working install.

Run a single file by path. Do not run the whole suite:
`tests/Volley/VolleyBundle/Controller/DefaultControllerTest.php` fails on a
fresh checkout because it needs `symfony/browser-kit` and
`symfony/dom-crawler`, neither of which is installed.

### Development server

`WebServerBundle` is not installed, so `bin/console server:run` does not exist.
Use PHP's built-in server with a front controller as the router script:

```bash
php -S 127.0.0.1:8000 -t web web/app_dev.php
```

Use `web/app.php` instead of `web/app_dev.php` to exercise the prod
front controller (real 404 pages, no profiler).

## Architecture

This is a **Symfony 4.3** application using a multi-bundle architecture under `src/Volley/`.

### Bundles

| Bundle | Path | Purpose |
|--------|------|---------|
| **VolleyFaceBundle** | `src/Volley/FaceBundle/` | Primary bundle — public-facing controllers, Twig templates, Doctrine entities, and frontend assets |
| **VolleyUserBundle** | `src/Volley/UserBundle/` | User authentication and profiles (extends FOSUserBundle) |
| **VolleyStatBundle** | `src/Volley/StatBundle/` | Statistics — teams, players, tournaments, seasons |
| **VolleyWebBundle** | `src/Volley/WebBundle/` | Additional web resources |

### Frontend assets (Gulp pipeline)
- **Sources:** `src/Volley/FaceBundle/Resources/public/` (CSS, SCSS, JS)
- **Output:** `web/css/` and `web/js/` (compiled, minified; do not edit directly)
- SCSS entry point: `src/Volley/FaceBundle/Resources/public/scss/custom/body.scss`
- Custom CSS: `src/Volley/FaceBundle/Resources/public/css/custom/`

### Templates
- Master layout: `app/Resources/views/layout.html.twig`
- Header: `app/Resources/views/header.html.twig`
- Admin layout: `app/Resources/views/admin.html.twig`
- Bundle views: `src/Volley/<Bundle>/Resources/views/`

### Configuration
- `app/config/config.yml` — framework, Twig, Doctrine, third-party bundle config
- `app/config/security.yml` — auth and access control
- `app/config/routing.yml` — route definitions
- `app/config/services.yml` — DI service definitions
- `app/config/parameters.yml` — DB credentials and API keys (not committed)

### Key libraries
- **Backend:** Doctrine ORM, FOSUserBundle, Gedmo Extensions (slugs/timestamps/tree), LiipImagineBundle, KnpPaginator, TinyMCE Bundle, PrestaSitemap
- **Frontend:** Bootstrap 3.3.7, jQuery 3.2.1, FontAwesome, Owl Carousel, Select2, FancyBox

## Authentication

Login is at `/login` (FOSUserBundle), and that is the whole public auth
surface. There is no public sign-up: the registration, password-resetting,
profile and change-password route imports are commented out in
`app/config/routing.yml` — only
`@FOSUserBundle/Resources/config/routing/security.xml` is active. They are left
in the file, commented, so the wiring stays discoverable.

Accounts are created and managed from the console:

```bash
bin/console fos:user:create <username> <email> <password>
bin/console fos:user:promote <username> ROLE_ADMIN
bin/console fos:user:change-password <username>
```

`bin/console fos:user:change-password` is the only way to reset a password —
`/resetting` no longer exists.

`tests/Volley/Security/AuthRoutesTest.php` locks this surface in: it fails if a
self-service auth route reappears, and also if `/login` ever moves.
