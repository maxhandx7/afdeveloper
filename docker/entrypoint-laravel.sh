#!/usr/bin/env bash
set -e
cd /app

# Carpetas de los volúmenes persistentes (pueden venir vacías la primera vez)
mkdir -p storage/app/private storage/framework/{cache,sessions,views} storage/logs public/images public/image
chown -R application:application storage public/images public/image

su application -s /bin/bash -c "php artisan migrate --force --no-interaction"
su application -s /bin/bash -c "php artisan optimize"
su application -s /bin/bash -c "php artisan filament:optimize" || true
