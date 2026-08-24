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
./vendor/bin/phpunit
```

### Development server
```bash
# Symfony built-in server (development only)
bin/console server:run
# App available at http://127.0.0.1:8000/app_dev.php
```

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
