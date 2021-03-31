FROM php:7.4-apache

# Arguments defined in docker-compose.yml
ARG AKAUNTING_DOCKERFILE_VERSION=0.1
ARG SUPPORTED_LOCALES="en_US.UTF-8"

RUN apt-get update \
 && apt-get -y upgrade --no-install-recommends \
 && apt-get install -y \
    build-essential \
    imagemagick \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libxml2-dev \
    libxrender1 \
    libzip-dev \
    locales \
    openssl \
    unzip \
    zip \
    zlib1g-dev \
    npm \
    git \
    --no-install-recommends \
 && apt-get clean && rm -rf /var/lib/apt/lists/*


RUN for locale in ${SUPPORTED_LOCALES}; do \
   sed -i 's/^# '"${locale}/${locale}/" /etc/locale.gen; done \
   && locale-gen

RUN docker-php-ext-configure gd \
   --with-freetype \
   --with-jpeg \
   && docker-php-ext-install -j$(nproc) \
   gd \
   bcmath \
   intl \
   mbstring \
   pcntl \
   pdo \
   pdo_mysql \
   zip

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install PHP Extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd zip intl imap xsl pgsql opcache bcmath mysqli pdo_mysql

    # Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

# RUN mkdir -p /var/www/akaunting
#  && curl -Lo /tmp/akaunting.zip 'https://akaunting.com/download.php?version=latest&utm_source=docker&utm_campaign=developers' \
# COPY Akaunting_2.1.8-Stable.zip /tmp/
# RUN unzip /tmp/Akaunting_2.1.8-Stable.zip -d /var/www/html
#  && rm -f /tmp/akaunting.zip


# COPY app/ /var/www/html/app/
# COPY bootstrap/ /var/www/html/bootstrap/
# COPY config/ /var/www/html/config/
# COPY database/ /var/www/html/database/
# COPY modules/ /var/www/html/modules/
# COPY overrides/ /var/www/html/overrides/
# COPY public/ /var/www/html/public/
# COPY resources/ /var/www/html/resources/
# COPY routes/ /var/www/html/routes/
# COPY storage/ /var/www/html/storage/
# COPY vendor/ /var/www/html/vendor/

# COPY web.config /var/www/html/
# COPY serviceworker.js /var/www/html/
# COPY nginx.example.com.conf /var/www/html/
# COPY manifest.json /var/www/html/
# COPY index.php /var/www/html/
# COPY composer.json /var/www/html/
# COPY artisan /var/www/html/
# COPY .env /var/www/html/

COPY files/akaunting.sh /usr/local/bin/akaunting.sh
COPY files/html /var/www/html
RUN cd /tmp && git clone https://github.com/hunzai/akaunting.git
RUN cp -r /tmp/akaunting/* /var/www/html/
RUN cd /var/www/html && composer install && npm install && npm run dev

RUN ls -la
ENTRYPOINT ["/usr/local/bin/akaunting.sh"]
CMD ["--start"]
