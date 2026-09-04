# ============================================================
# Stage 1: Build frontend
# ============================================================
FROM node:20-alpine AS node-build

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public

RUN npm run build


# ============================================================
# Stage 2: Laravel PHP (PHP-FPM + Nginx)
# ============================================================
FROM php:8.3-fpm

# Install dependency sistem, nginx, dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    git \
    nginx \
    gettext-base \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        gd \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        bcmath \
    && rm -rf /var/lib/apt/lists/*

    # Tuning OPcache untuk production
RUN { \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.memory_consumption=192'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Folder aplikasi
WORKDIR /var/www/html


# Copy source Laravel
COPY . .


# Copy hasil build Vite
COPY --from=node-build /app/public/build ./public/build


# Install dependency Laravel
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction


# Permission Laravel
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    && chmod -R 775 \
    storage \
    bootstrap/cache


# Konfigurasi Nginx (template — port diisi saat container start, karena Railway assign port dinamis)
COPY docker/nginx.conf.template /etc/nginx/conf.d/laravel.conf.template
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf 2>/dev/null || true


# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh


EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]