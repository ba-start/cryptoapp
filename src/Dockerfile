# Base image with PHP + FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev git unzip curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel optimizations
RUN php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Run migrations & import coins (optional; can skip in worker if needed)
RUN php artisan migrate --force \
    && php artisan coins:import --top=1000 --per_page=250

# Expose port for Render web service
EXPOSE 8080

# Entrypoint: choose web or worker based on SERVICE_TYPE
CMD if [ "$SERVICE_TYPE" = "worker" ]; then \
        php artisan queue:work --sleep=3 --tries=3; \
    else \
        php artisan serve --host=0.0.0.0 --port=8080; \
    fi
