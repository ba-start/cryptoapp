# CryptoApp - Laravel Docker Setup

A Laravel-based cryptocurrency WatchDog app running fully in Docker.

## Prerequisites

- Docker & Docker Compose installed
- Git

## Quick start after cloning repo
```bash
cp src/.env.example src/.env    # fill in DB_PASSWORD match the password in docker-compose.yml (manually)
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
```bash
git clone https://github.com/yourusername/crypto-watchdog.git
cd crypto-watchdog
```
2. **Copy .env example**
```bash
cp .env.example .env
```
***Fill in DB_PASSWORD:***
```bash
match the password in docker-compose.yml
```
***Fill in APP_KEY:***
```bash
docker compose run --rm app php artisan key:generate
```
***Fill in JWT_SECRET:***
```bash
docker compose run --rm app php artisan jwt:secret
```
***3. Build and start Docker containers***
```bash
docker compose up -d --build
```
***4. Run migrations and seed database***
```bash
docker compose run --rm app php artisan migrate --seed
```
***4.2 (Optional) Import Coins***
```bash
docker compose exec app php artisan coins:import --top=1000 --per_page=250
```
***5. Run queue worker***
```bash
docker compose exec app php artisan queue:work
```
***6. Access the app***

Open http://localhost:8000

---

## In Case of Cipher error : 

cached .env - (rebuild container)

```bash
chmod 664 src/.env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
```


## Scheduled Tasks

ImportCoinsJob → hourly (fetch top coins)
CheckWatchdogs → every minute (send alert emails if targets reached)

Ensure the queue worker is running to process scheduled jobs.

## MailHog (Email testing)

Accessible at http://localhost:8025

