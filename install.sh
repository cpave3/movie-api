#!/bin/bash
cp .env.example .env
echo "[*] Bringing containers online"
docker-compose up -d
echo "[*] Installing PHP dependencies"
docker run --rm --interactive --tty --volume $PWD:/app composer install
echo "[*] Installing Database Migrations"
docker-compose exec app php artisan migrate --seed
echo "[*] Installation Complete"
