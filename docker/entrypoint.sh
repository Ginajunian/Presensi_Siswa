#!/bin/bash
set -e

echo "========================================"
echo "Starting Laravel application"
echo "========================================"

PORT=${PORT:-8080}

echo "Preparing database..."

if [ -z "$MYSQLHOST" ]; then
    echo "ERROR: MYSQLHOST belum tersedia."
    exit 1
fi

if [ -z "$MYSQLUSER" ]; then
    echo "ERROR: MYSQLUSER belum tersedia."
    exit 1
fi

if [ -z "$MYSQLPASSWORD" ]; then
    echo "ERROR: MYSQLPASSWORD belum tersedia."
    exit 1
fi

if [ -z "$MYSQLDATABASE" ]; then
    echo "ERROR: MYSQLDATABASE belum tersedia."
    exit 1
fi

echo "MySQL Host: $MYSQLHOST"
echo "MySQL Port: ${MYSQLPORT:-3306}"
echo "MySQL Database: $MYSQLDATABASE"

echo "Waiting for MySQL..."

echo "Testing koneksi MySQL..."

mysql \
    --skip-ssl \
    -h "$MYSQLHOST" \
    -P "${MYSQLPORT:-3306}" \
    -u "$MYSQLUSER" \
    -p"$MYSQLPASSWORD" \
    -e "SELECT 1;" "$MYSQLDATABASE"

echo "Koneksi MySQL berhasil."

echo "MySQL sudah siap."

echo "Preparing storage..."

php artisan storage:link --force || true

echo "Caching Laravel configuration..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "========================================"
echo "Starting Laravel server"
echo "========================================"

exec php artisan serve \
    --host=0.0.0.0 \
    --port=${PORT}