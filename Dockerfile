FROM php:8.4-apache-bookworm
WORKDIR /var/www/html
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libonig-dev libxml2-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j2 pdo_pgsql gd mbstring zip calendar opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-plugins --no-scripts --optimize-autoloader
COPY . .
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/pkb.ini
COPY docker/entrypoint.sh /usr/local/bin/pkb-entrypoint
RUN chmod 755 /usr/local/bin/pkb-entrypoint \
    && mkdir -p assets protected/runtime uploads downloadsfile template_laporan/hasil \
    && chown -R www-data:www-data assets protected/runtime uploads downloadsfile template_laporan/hasil
EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD php -r '$c = stream_context_create(["http" => ["timeout" => 3]]); $r = @file_get_contents("http://127.0.0.1/site/health", false, $c); exit($r !== false && json_decode($r, true) === ["status" => "ok"] ? 0 : 1);'
ENTRYPOINT ["pkb-entrypoint"]
CMD ["apache2-foreground"]
