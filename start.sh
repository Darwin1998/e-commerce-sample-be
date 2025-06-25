#!/bin/bash

echo "🚀 Fixing permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

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
