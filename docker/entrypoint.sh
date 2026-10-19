#!/bin/sh
set -eu
if [ "${1:-}" = "apache2-foreground" ]; then
    : "${DB_HOST:?DB_HOST wajib diisi}"
    : "${DB_DATABASE:?DB_DATABASE wajib diisi}"
    : "${DB_USERNAME:?DB_USERNAME wajib diisi}"
    : "${DB_PASSWORD:?DB_PASSWORD wajib diisi}"
    for directory in assets protected/runtime uploads downloadsfile template_laporan/hasil; do
        mkdir -p "/var/www/html/$directory"
        chown www-data:www-data "/var/www/html/$directory"
        chmod 775 "/var/www/html/$directory"
    done
fi
exec docker-php-entrypoint "$@"
