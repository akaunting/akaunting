FROM php:apache

# Install GD, Mysql & ZIP extension
RUN requirementsToKeep="zip" \
    && requirementsToRemove="libzip-dev libpng-dev" \
    && phpModules="pdo_mysql gd zip" \
    && apt-get update && apt-get install -y $requirementsToKeep $requirementsToRemove \
    && docker-php-ext-install $phpModules \
    && apt-get purge --auto-remove -y $requirementsToRemove \
    && rm -rf /var/lib/apt/lists/*

# Allow rewrite
RUN a2enmod rewrite

COPY . /var/www/html/

# Authorize these folders to be edited
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache
