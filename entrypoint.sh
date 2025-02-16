#!/bin/bash

# Exit if any command fails
set -e

# Wait for MySQL to be ready
echo "Waiting for the database to start..."
while ! mysqladmin ping -h"$DB_HOST" --silent; do
    sleep 2
done
echo "Database is ready!"

# Install Composer dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Generate application key
echo "Generating Laravel application key..."
php artisan key:generate

# Run Akaunting installation
# echo "Installing Akaunting..."
# php artisan install --db-name="akaunting_database" --db-username="akaunting_user" --db-password="akaunting_pass" --admin-email="admin@akaunting.com" --admin-password="AdminPass"

# Run database migrations
# echo "Running database migrations..."
# php artisan migrate --seed --force

# Set permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache
echo "Starting Apache..."
apachectl -D FOREGROUND
