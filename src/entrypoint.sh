#!/bin/sh
set -e

# If web service
if [ "$SERVICE_TYPE" = "web" ]; then
    echo "Starting Laravel web service..."

    # Ensure .env exists
    if [ ! -f .env ]; then
        cp .env.example .env
        php artisan key:generate --force
    fi

    # Laravel optimization
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Run migrations (optional, can be removed if you prefer manual)
    php artisan migrate --force

    # Start Laravel server
    php artisan serve --host=0.0.0.0 --port=8080

# If worker service
elif [ "$SERVICE_TYPE" = "worker" ]; then
    echo "Starting Laravel queue worker..."
    php artisan queue:work --sleep=3 --tries=3

else
    echo "Unknown SERVICE_TYPE: $SERVICE_TYPE"
    exit 1
fi
