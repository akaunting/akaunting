FROM php:8.2-apache

# ======================
# System dependencies
# ======================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libicu-dev

# ======================
# PHP extensions
# ======================
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    zip \
    xml \
    dom \
    intl

# ======================
# Apache config
# ======================
RUN a2enmod rewrite

# ======================
# Install Composer
# ======================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ======================
# App setup
# ======================
WORKDIR /var/www/html
COPY . .

# Install PHP dependencies (NO npm)
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

