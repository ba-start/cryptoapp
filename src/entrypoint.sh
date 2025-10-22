#!/bin/sh
set -e

# If web service
if [ "$SERVICE_TYPE" = "web" ]; then
    echo "Starting Laravel web service..."

    # Ensure .env exists
    if [ ! -f .env ]; then
        cp .env.example .env
    fi

    # Laravel optimization
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Run migrations (optional, can be removed if you prefer manual)
    php artisan migrate --force
    
    # Import top 1000 coins (web service only)
    if [ "$SERVICE_TYPE" = "web" ] && [ ! -f .coins_imported ]; then
        php artisan coins:import --top=1000 --per_page=250
        touch .coins_imported
    fi

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
