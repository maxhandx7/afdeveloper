# -------- Stage 1: Build assets --------
FROM node:18 AS nodebuilder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run production


# -------- Stage 2: PHP + Nginx --------
FROM webdevops/php-nginx:7.4

WORKDIR /app

COPY . /app

# Copiamos assets compilados
COPY --from=nodebuilder /app/public /app/public

RUN composer install --no-dev --optimize-autoloader

RUN chown -R application:application /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

ENV WEB_DOCUMENT_ROOT=/app/public

EXPOSE 80
