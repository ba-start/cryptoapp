# Base PHP image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zlib1g-dev libonig-dev libpng-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
    
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

# Ensure entrypoint.sh is executable
RUN chmod +x /var/www/html/entrypoint.sh

# Use entrypoint script for both web and worker
CMD ["/var/www/html/entrypoint.sh"]
