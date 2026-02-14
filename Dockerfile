FROM webdevops/php-nginx:7.4

WORKDIR /app

# Instalar Node (versión compatible con Laravel Mix viejo)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get update \
    && apt-get install -y nodejs

COPY . /app

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Instalar dependencias JS y compilar assets
RUN npm install
RUN npm run prod

# Permisos Laravel
RUN chown -R application:application /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 80
