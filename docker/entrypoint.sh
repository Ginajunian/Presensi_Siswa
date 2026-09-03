#!/bin/bash
set -e

# Railway memberikan PORT melalui environment variable
PORT=${PORT:-80}

# Sesuaikan Apache dengan PORT Railway
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Jalankan migration
php artisan migrate --force

# Buat/update symbolic link storage
php artisan storage:link --force

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan Apache
exec apache2-foreground