# ---------- Stage 1: build asset frontend (Vite) ----------
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY resources resources
COPY vite.config.js ./
COPY public public
RUN npm run build

# ---------- Stage 2: aplikasi PHP produksi ----------
FROM php:8.3-apache

# Install ekstensi sistem & PHP yang dibutuhkan aplikasi
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev libonig-dev unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql mbstring zip exif bcmath \
    && rm -rf /var/lib/apt/lists/*

# Copy Composer dari image resminya (bukan install manual)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy seluruh source code aplikasi
COPY . .

# Copy hasil build asset Vite dari stage 1
COPY --from=node-build /app/public/build public/build

# Install dependency PHP untuk produksi (tanpa paket dev/testing)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Arahkan DocumentRoot Apache ke folder public/ (cara resmi dari image php:apache)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set kepemilikan folder yang perlu ditulis Laravel saat runtime
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]