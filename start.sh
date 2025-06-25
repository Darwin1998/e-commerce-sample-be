#!/bin/bash

echo "🚀 Ensuring necessary directories exist..."
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/framework/cache

echo "🔐 Fixing permissions for storage and cache..."
find /var/www/storage -type d -exec chmod 775 {} \;
find /var/www/storage -type f -exec chmod 664 {} \;
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "🔗 Linking storage..."
php artisan storage:link || true

echo "🧠 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "✅ Caching config..."
php artisan config:cache

echo "📦 Running migrations..."
php artisan migrate --force || true

echo "🌱 Running seeders (if any)..."
php artisan db:seed --force || true

echo "🎉 Laravel app is ready. Starting Apache..."
exec apache2-foreground
