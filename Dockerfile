# syntax=docker/dockerfile:1
FROM node:24-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json .npmrc ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM dunglas/frankenphp:php8.4-bookworm AS base
WORKDIR /app
RUN apt-get update && apt-get install -y --no-install-recommends supervisor curl unzip \
    && rm -rf /var/lib/apt/lists/*
RUN install-php-extensions pdo_pgsql pdo_mysql pdo_sqlite redis pcntl intl zip gd bcmath opcache exif sockets mbstring dom xml xmlwriter
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache

FROM base AS test
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
COPY --from=assets /app/public/build ./public/build
CMD ["php", "artisan", "test"]

FROM base AS production
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && cp vendor/laravel/octane/src/Commands/stubs/frankenphp-worker.php public/frankenphp-worker.php \
    && ln -s /app/storage/app/public public/storage \
    && mkdir -p /var/lib/caddy/.local/share /var/lib/caddy/.config \
    && chown -R www-data:www-data storage bootstrap/cache /var/lib/caddy
COPY --from=assets /app/public/build ./public/build
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod 755 /entrypoint.sh
ENV XDG_DATA_HOME=/var/lib/caddy/.local/share \
    XDG_CONFIG_HOME=/var/lib/caddy/.config \
    OCTANE_SERVER=frankenphp \
    OCTANE_WORKERS=2 \
    OCTANE_MAX_REQUESTS=500
EXPOSE 8000
HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD curl --fail --silent http://127.0.0.1:8000/up || exit 1
ENTRYPOINT ["/entrypoint.sh"]
