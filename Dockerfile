FROM php:apache
COPY . /var/www/html/

# Authorize these folders to be edited
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Install ZIP extension
RUN apt-get update && \
    apt-get install -y --no-install-recommends zip libzip-dev
RUN pecl install zip && \
    docker-php-ext-enable zip

# Allow rewrite
RUN a2enmod rewrite
