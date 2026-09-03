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

MYSQL_CMD="mysql --skip-ssl -h $MYSQLHOST -P ${MYSQLPORT:-3306} -u $MYSQLUSER -p$MYSQLPASSWORD"

echo "MySQL Host: $MYSQLHOST"
echo "MySQL Port: ${MYSQLPORT:-3306}"
echo "MySQL Database: $MYSQLDATABASE"

echo "Testing koneksi MySQL..."

$MYSQL_CMD -e "SELECT 1;" "$MYSQLDATABASE"

echo "Koneksi MySQL berhasil."

# ============================================================
# IMPORT DATABASE LOKAL
# ============================================================

echo "========================================"
echo "Mengimpor database presensi_siswa.sql"
echo "========================================"

if [ -f "/var/www/html/presensi_siswa.sql" ]; then

    echo "Menghapus tabel lama..."

    $MYSQL_CMD -N -e "
    SELECT CONCAT('DROP TABLE IF EXISTS \`', table_name, '\`;')
    FROM information_schema.tables
    WHERE table_schema = '$MYSQLDATABASE';
    " "$MYSQLDATABASE" > /tmp/drop_tables.sql

    (
        echo "SET FOREIGN_KEY_CHECKS=0;"
        cat /tmp/drop_tables.sql
        echo "SET FOREIGN_KEY_CHECKS=1;"
    ) > /tmp/reset_database.sql

    $MYSQL_CMD "$MYSQLDATABASE" < /tmp/reset_database.sql

    echo "Tabel lama berhasil dihapus."

    echo "Import database lokal..."

    $MYSQL_CMD "$MYSQLDATABASE" < /var/www/html/presensi_siswa.sql

    echo "========================================"
    echo "IMPORT DATABASE BERHASIL"
    echo "========================================"

else

    echo "ERROR: presensi_siswa.sql tidak ditemukan."
    exit 1

fi

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