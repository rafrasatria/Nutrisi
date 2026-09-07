#!/bin/sh
set -e

# Render/Koyeb menyuntik $PORT; Hugging Face Spaces memakai 7860 (lihat README).
PORT="${PORT:-7860}"
sed -i "s/Listen 80/Listen $PORT/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/" /etc/apache2/sites-available/000-default.conf

# Migrasi (termasuk tabel sessions/cache/jobs, kelas, soal, misi_results) + seed awal
php artisan migrate --force
php artisan db:seed --force || true

# Cache konfigurasi untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
