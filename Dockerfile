FROM webdevops/php-nginx:7.4

WORKDIR /app

COPY . /app

RUN composer install --no-dev --optimize-autoloader

RUN chown -R application:application /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache


ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 80
