#!/bin/sh
set -eu
cd /app
# Explicit commands (e.g. migration) bypass web startup.
if [ "$#" -gt 0 ]; then
    exec "$@"
fi
: "${APP_KEY:?APP_KEY must be configured in Portainer}"
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache
# Remove process state left behind by a replaced container.
rm -f storage/logs/octane-server-state.json
# Do not flush the shared application cache or run database migrations here.
su -s /bin/sh www-data -c 'php artisan config:clear && php artisan route:clear && php artisan view:clear'
su -s /bin/sh www-data -c 'php artisan config:cache && php artisan route:cache && php artisan view:cache'
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
