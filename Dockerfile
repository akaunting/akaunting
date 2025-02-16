FROM php:8.2-apache

# Instalar dependências do sistema
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

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar o Node.js e o NPM
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - && \
    apt-get install -y nodejs

# Instalar dependências do projeto
WORKDIR /var/www/html

# Copiar os arquivos do repositório para o container
COPY . /var/www/html/

# Instalar dependências do Composer e NPM
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run dev

# Expor a porta 80
EXPOSE 80

# Copiar o script de entrada
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Usar o script como ponto de entrada
ENTRYPOINT ["/entrypoint.sh"]
