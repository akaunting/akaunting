FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
    imagemagick \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libpq-dev \
    libxrender1 \
    locales \
    openssh-client \
    patch \
    unzip \
    zlib1g-dev \
    --no-install-recommends && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN cp /usr/share/i18n/SUPPORTED /etc/locale.gen
RUN locale-gen

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/

RUN docker-php-ext-install \
    gd \
    pcntl \
    pdo_mysql \
    zip

RUN pecl channel-update pecl.php.net \
    && printf "\n" | pecl install mcrypt-1.0.2 \
    && printf "\n" | pecl install xdebug-2.7.2 \
    && docker-php-ext-enable mcrypt xdebug

COPY conf/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY conf/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf