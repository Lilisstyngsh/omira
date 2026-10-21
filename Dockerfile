# Build frontend assets with the Node version supported by Vite 7.
FROM node:22-bookworm-slim AS frontend

ENV NODE_USE_SYSTEM_CA=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates \
    && update-ca-certificates \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /build
COPY package.json ./
RUN npm install --no-audit --no-fund
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build

# Runtime image for Laravel 12.
FROM php:8.3-apache AS app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pdo_mysql \
        zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

# .dockerignore excludes local .env files, vendor, cache files, and SQL exports.
COPY --chown=www-data:www-data . .
COPY --from=frontend --chown=www-data:www-data /build/public/build ./public/build
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/entrypoint.sh /usr/local/bin/omira-entrypoint

RUN chmod +x /usr/local/bin/omira-entrypoint \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs storage/app/public bootstrap/cache \
    && composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --no-progress \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && rm -rf /root/.composer/cache

EXPOSE 80
ENTRYPOINT ["omira-entrypoint"]
CMD ["apache2-foreground"]
