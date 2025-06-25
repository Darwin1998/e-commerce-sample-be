#!/bin/bash

echo "🔐 Fixing storage permissions..."
mkdir -p /var/www/storage/logs /var/www/storage/framework/cache
touch /var/www/storage/logs/laravel.log

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public
chmod -R ug+rwX /var/www/storage /var/www/bootstrap/cache /var/www/public

echo "🔗 Linking storage..."
php artisan storage:link || true

echo "🧠 Clearing and caching config..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache

echo "📦 Running migrations..."
php artisan migrate --force || true

echo "🌱 Running seeders..."
php artisan db:seed --force || true

# OPTIONAL: If you're using custom themes or plugins with assets
php artisan filament:assets || true

echo "🎉 Starting Apache..."
exec apache2-foreground
