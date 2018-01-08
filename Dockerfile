FROM php:apache

RUN apt-get update && apt-get install -y zip libzip-dev libpng-dev \
    && docker-php-ext-install pdo_mysql gd zip \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

# Authorize these folders to be edited
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Allow rewrite
RUN a2enmod rewrite
