FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev git unzip curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

# Entrypoint script
CMD if [ "$SERVICE_TYPE" = "worker" ]; then \
        php artisan queue:work --sleep=3 --tries=3; \
    else \
        # Ensure APP_KEY exists
        if [ ! -f .env ]; then
            cp .env.example .env
            php artisan key:generate --force
        fi
        php artisan config:cache \
            && php artisan route:cache \
            && php artisan view:cache \
            && php artisan serve --host=0.0.0.0 --port=8080; \
    fi
