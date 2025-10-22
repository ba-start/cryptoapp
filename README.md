# CryptoApp - Laravel Docker Setup

A Laravel-based cryptocurrency WatchDog app running fully in Docker.

## Prerequisites

- Docker & Docker Compose installed
- Git

## Quick start after cloning repo
```bash
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan coins:import --top=1000 --per_page=250
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

docker compose exec app php artisan queue:work
```

## Setup

1. **Clone the repository**

git clone https://github.com/yourusername/crypto-watchdog.git
cd crypto-watchdog

2. Copy .env example
cp .env.example .env

Fill in APP_KEY:
docker compose run --rm app php artisan key:generate

Fill in JWT_SECRET:
docker compose run --rm app php artisan jwt:secret

3. Build and start Docker containers
docker compose up -d --build

4. Run migrations and seed database
docker compose run --rm app php artisan migrate --seed

5. Run queue worker
docker compose exec app php artisan queue:work

6. Access the app

Open http://localhost:8000

---

Scheduled Tasks

ImportCoinsJob → hourly (fetch top coins)
CheckWatchdogs → every minute (send alert emails if targets reached)

Ensure the queue worker is running to process scheduled jobs.

MailHog (Email testing)

Accessible at http://localhost:8025

