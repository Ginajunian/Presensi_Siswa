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
# Stage 2: Laravel PHP
# ============================================================
FROM php:8.3-cli

# Install dependency sistem dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    git \
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


# Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh


# Railway menggunakan PORT
EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]