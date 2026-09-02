#!/bin/bash
set -e

# Railway kasih tau nomor port lewat variabel PORT (beda-beda tiap deploy),
# kita "suntikkan" nomor itu ke konfigurasi Apache saat container baru nyala.
PORT=${PORT:-80}
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/*.conf

# Jalankan migrasi database (aman dijalankan berulang kali setiap deploy —
# Laravel otomatis skip migration yang sudah pernah dijalankan sebelumnya)
php artisan migrate --force

# Pastikan symlink storage (foto siswa, dll) selalu ada
php artisan storage:link || true

# Cache konfigurasi & route untuk performa produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground