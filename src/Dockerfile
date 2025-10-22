# Base PHP image
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

# Expose port for web service
EXPOSE 8080

# Use entrypoint script for both web and worker
CMD ["/var/www/html/entrypoint.sh"]
