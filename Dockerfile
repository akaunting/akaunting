FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    apache2 \
    unzip \
    curl \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    libpng-dev \
    git \
    libmariadb-dev \
    npm \
    libicu-dev \
    mariadb-client \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring xml curl zip gd bcmath intl \
    && a2enmod rewrite \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && service apache2 start

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js and NPM
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

# Set project working directory
WORKDIR /var/www/html

# Copy repository files to the container
COPY . /var/www/html/

# Install Composer and NPM dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run dev

# Expose port 80
EXPOSE 80

# Copy the entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Use the script as the entrypoint
ENTRYPOINT ["/entrypoint.sh"]
