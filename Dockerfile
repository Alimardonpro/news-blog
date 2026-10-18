# 1-BOSQICH: Node orqali frontend assetlarni (Vite) yig'ish
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# 2-BOSQICH: PHP 8.2 va Nginx asosiy konteyner
FROM php:8.2-fpm-alpine

# Kerakli paketlar va Nginx
RUN apk add --no-cache \
    nginx \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git

# PHP kengaytmalarini o'rnatish (PostgreSQL, GD, Zip, OPcache, BCMath)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip opcache bcmath

# Composer o'rnatish
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Ilova fayllarini nusxalash
COPY . .
COPY --from=node_builder /app/public/build ./public/build

# Composer dependencylarni o'rnatish (Production optimizatsiyasi bilan)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Nginx va startup scriptlarni ko'chirish
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh

# Fayl ruxsatlarini sozlash
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/usr/local/bin/run.sh"]
