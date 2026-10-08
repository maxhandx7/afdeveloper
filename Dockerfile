# ─────────────────────────────────────────────────────────────
#  AFDeveloper — Laravel 13 + Filament 5
#  Imagen única: Nginx + PHP-FPM + cola + scheduler (supervisor)
#  Pensada para Coolify (Traefik delante, puerto 80).
# ─────────────────────────────────────────────────────────────
FROM webdevops/php-nginx:8.4

ENV WEB_DOCUMENT_ROOT=/app/public \
    PHP_MEMORY_LIMIT=256M \
    PHP_UPLOAD_MAX_FILESIZE=20M \
    PHP_POST_MAX_SIZE=25M \
    PHP_DATE_TIMEZONE=America/Bogota \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0

WORKDIR /app

# 1) Dependencias primero (capa cacheable mientras no cambie composer.json)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# 2) Código
COPY . .
RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && php artisan filament:assets \
    && chown -R application:application /app/storage /app/bootstrap/cache /app/public \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# 3) Cola de correos y tareas programadas, manejadas por supervisor
COPY docker/supervisor-laravel.conf /opt/docker/etc/supervisor.d/laravel.conf

# 4) Al arrancar: migrar y cachear configuración (ya con las variables de Coolify)
COPY docker/entrypoint-laravel.sh /opt/docker/provision/entrypoint.d/30-laravel.sh
RUN chmod +x /opt/docker/provision/entrypoint.d/30-laravel.sh

EXPOSE 80
