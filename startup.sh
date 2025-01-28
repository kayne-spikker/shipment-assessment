#!/bin/bash

# Exit immediately if any command exits with a non-zero status
set -e

gem install mailcatcher

# Create a .env file if it doesn't already exist
if [ ! -f .env ]; then
    echo "Creating .env file with APP_KEY=..."
    echo "APP_KEY=" > .env
    echo ".env file created with APP_KEY=."
else
    echo ".env file already exists."
fi

# Run Laravel commands
echo "Generating application key..."
php artisan key:generate

echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Re-caching configuration and views..."
php artisan config:cache
php artisan view:cache

# Start PHP built-in server
echo "Starting PHP built-in server on 0.0.0.0:8000..."
php -S 0.0.0.0:8000 -t public/

mailcatcher