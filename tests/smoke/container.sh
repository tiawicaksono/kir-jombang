#!/usr/bin/env bash
set -euo pipefail
image=${1:?Pass test image}
container="pkb-smoke-${RANDOM}"
cleanup() { docker logs "$container" || true; docker rm -f "$container" >/dev/null || true; }
trap cleanup EXIT
docker run -d --name "$container" -p 127.0.0.1::80 \
  -e APP_ENV=staging -e APP_DEBUG=false \
  -e DB_HOST=unreachable.invalid -e DB_DATABASE=smoke \
  -e DB_USERNAME=smoke -e DB_PASSWORD=smoke "$image"
port=$(docker port "$container" 80/tcp | awk -F: '{print $NF}')
base="http://127.0.0.1:$port"
ready=false
for attempt in $(seq 1 30); do
  if curl -fsS "$base/site/health" > /tmp/pkb-health.json; then ready=true; break; fi
  sleep 1
done
[ "$ready" = true ]
php -r 'exit(json_decode(file_get_contents("/tmp/pkb-health.json"), true) === ["status" => "ok"] ? 0 : 1);'
for path in /protected/config/main.php /vendor/autoload.php /.git/config /composer.json /docker/php.ini /tests/smoke/runtime.php; do
  code=$(curl -s -o /dev/null -w '%{http_code}' "$base$path")
  [ "$code" = 403 ] || { echo "Unexpected status $code for $path"; exit 1; }
done
code=$(curl -s -o /dev/null -w '%{http_code}' "$base/site/nonexistent")
[ "$code" = 404 ]
echo 'PASS: HTTP health, private paths denied, error handler'
