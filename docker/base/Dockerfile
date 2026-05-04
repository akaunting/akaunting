FROM php:8.1-apache

ARG DEBIAN_FRONTEND=noninteractive
ARG SUPPORTED_LOCALES="en_US.UTF-8"

RUN set -eux; \
    apt-get update; \
    apt-get -y upgrade --no-install-recommends; \
    apt-get install -y --no-install-recommends \
      build-essential \
      curl \
      git \
      libfreetype6-dev \
      libicu-dev \
      libjpeg62-turbo-dev \
      libonig-dev \
      libpng-dev \
      libzip-dev \
      locales \
      unzip \
      zip; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    for locale in ${SUPPORTED_LOCALES}; do \
      sed -i "s/^# ${locale}/${locale}/" /etc/locale.gen; \
    done; \
    locale-gen

RUN set -eux; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
      bcmath \
      curl \
      gd \
      intl \
      mbstring \
      pdo_mysql \
      xml \
      zip

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

COPY akaunting.sh /usr/local/bin/akaunting.sh

RUN chmod +x /usr/local/bin/akaunting.sh

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/akaunting.sh"]
CMD ["--start"]
