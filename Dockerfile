# syntax=docker/dockerfile:1

FROM serversideup/php:8.4-cli AS builder

USER root

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update && apt-get install -y nodejs --no-install-recommends \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install --no-dev --no-interaction --no-autoloader --no-scripts --prefer-dist

RUN npm ci

COPY . .

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

RUN npm run build

FROM serversideup/php:8.4-frankenphp AS production

USER root

RUN install-php-extensions intl pdo_pgsql

USER www-data

ENV AUTORUN_ENABLED=true \
    AUTORUN_LARAVEL_MIGRATION_TIMEOUT=120 \
    AUTORUN_LARAVEL_MIGRATION=true \
    AUTORUN_LARAVEL_MIGRATION_FORCE=true \
    AUTORUN_LARAVEL_MIGRATION_SEED=true \
    AUTORUN_LARAVEL_OPTIMIZE=true \
    AUTORUN_LARAVEL_CONFIG_CACHE=false \
    AUTORUN_LARAVEL_ROUTE_CACHE=false \
    AUTORUN_LARAVEL_VIEW_CACHE=false \
    AUTORUN_LARAVEL_EVENT_CACHE=true \
    AUTORUN_LARAVEL_STORAGE_LINK=true \
    HEALTHCHECK_PATH=/up \
    SSL_MODE=off \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    CADDY_SERVER_ROOT=/var/www/html/public

WORKDIR /var/www/html

COPY --chown=www-data:www-data artisan composer.json composer.lock ./
COPY --chown=www-data:www-data app ./app
COPY --chown=www-data:www-data bootstrap ./bootstrap
COPY --chown=www-data:www-data config ./config
COPY --chown=www-data:www-data database/migrations ./database/migrations
COPY --chown=www-data:www-data database/seeders ./database/seeders
COPY --chown=www-data:www-data README.md ./
COPY --chown=www-data:www-data docs ./docs
COPY --chown=www-data:www-data resources/views ./resources/views
COPY --chown=www-data:www-data routes ./routes
COPY --chown=www-data:www-data src ./src
COPY --chown=www-data:www-data storage ./storage

COPY --from=builder --chown=www-data:www-data /app/vendor ./vendor
COPY --from=builder --chown=www-data:www-data /app/public ./public

RUN mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} storage/app/public storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD curl -f http://127.0.0.1:8080/up || exit 1
