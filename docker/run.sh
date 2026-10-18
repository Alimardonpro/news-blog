#!/bin/sh
set -e

echo "Starting deployment setup..."

# Storage link yaratish
php artisan storage:link || true

# Cache lar
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# PHP-FPM ni orqa fonda ishga tushirish
php-fpm -D

# Nginx ni asosiy jarayon sifatida ishga tushirish
echo "Starting Nginx..."
exec nginx -g "daemon off;"
