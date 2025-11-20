# Simple Dockerfile for a PHP (Akaunting) app — edit PHP version and extensions as needed.
FROM php:8.1-apache

# Install required system packages and PHP extensions (adjust as needed)
RUN apt-get update && apt-get install -y git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --prefer-dist --no-interaction --no-progress

# Set permissions (adjust as needed)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]