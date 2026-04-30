FROM akaunting/akaunting:latest

USER root
WORKDIR /var/www/html

COPY . /tmp/akaunting-src

RUN set -eux; \
    cp -a /tmp/akaunting-src/. /var/www/html/; \
    rm -rf /tmp/akaunting-src; \
    chown -R www-data:www-data /var/www/html
