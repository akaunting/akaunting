FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libicu-dev libzip-dev zip unzip \
    && docker-php-ext-install \
        pdo_mysql mbstring gd bcmath intl zip \
    && a2enmod rewrite

RUN git config --global --add safe.directory /var/www/html

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-interaction --prefer-dist \
    && npm install \
    && cp .env.example .env \
    && php artisan key:generate \
    && chmod -R 755 storage bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]