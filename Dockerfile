FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
# Install PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zlib1g-dev libonig-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer (copied from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy only composer files first (for better caching)
COPY ./src/composer.json ./src/composer.lock* ./

# Install dependencies (no dev by default; keep --no-scripts until after copying source)
RUN composer install --no-interaction --prefer-dist --no-scripts

# Copy entire Laravel project
COPY ./src /var/www/html

# Fix permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run composer scripts and Laravel optimizations
RUN composer dump-autoload --optimize \
    && php artisan key:generate --ansi || true \
    && php artisan package:discover --ansi || true

# Expose the port Laravel will serve from
EXPOSE 8000

# Default command (PHP built-in server for simplicity)
CMD php -S 0.0.0.0:8000 -t public
