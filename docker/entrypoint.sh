#!/usr/bin/env bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
  cp .env.example .env
fi

# Keep Docker Compose APP_KEY authoritative (avoid empty .env APP_KEY blanking config)
if [ -n "${APP_KEY:-}" ]; then
  if grep -q '^APP_KEY=' .env; then
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
  else
    echo "APP_KEY=${APP_KEY}" >> .env
  fi
else
  php artisan key:generate --force --ansi || true
fi

echo "Waiting for MySQL..."
for i in $(seq 1 60); do
  if php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'ok'; } catch (Exception \$e) { exit(1); }" 2>/dev/null; then
    break
  fi
  sleep 2
done

php artisan migrate --force --ansi
php artisan db:seed --force --ansi
php artisan storage:link --force --ansi || true
php artisan config:clear --ansi || true

exec php artisan serve --host=0.0.0.0 --port=8000
