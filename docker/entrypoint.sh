#!/bin/bash
set -e

PORT=${PORT:-80}

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

echo "===== CEK APACHE MPM ====="
apache2ctl -M 2>&1 | grep mpm || true

echo "===== MODS ENABLED MPM ====="
ls -la /etc/apache2/mods-enabled/ | grep mpm || true

echo "===== LOADMODULE MPM ====="
grep -R "LoadModule mpm_" /etc/apache2 2>/dev/null || true

echo "===== AKHIR CEK MPM ====="

php artisan migrate --force

php artisan storage:link --force || true

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground