#!/bin/bash
set -e

echo "========================================"
echo "Starting Laravel application"
echo "========================================"

echo "Preparing database..."

php artisan migrate --force

echo "Preparing storage..."

php artisan storage:link --force || true

echo "Caching Laravel configuration..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "========================================"
echo "Starting Laravel server"
echo "========================================"

PORT=${PORT:-8080}

exec php artisan serve \
    --host=0.0.0.0 \
    --port=${PORT}