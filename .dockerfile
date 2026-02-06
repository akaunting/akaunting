# Utilise une image PHP avec extensions pour Laravel
FROM php:8.2-fpm

# Installer des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet dans le conteneur
WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP
RUN composer install

# Donner les bons droits sur le stockage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

