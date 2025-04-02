FROM php:8.2 AS builder

# Install system dependencies
RUN apt-get update -y && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev libmcrypt-dev libmagickwand-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Final image
FROM php:8.2
WORKDIR /var/www
COPY --from=builder /var/www ./
RUN chown -R www-data:www-data /var/www && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8181
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8181"]
