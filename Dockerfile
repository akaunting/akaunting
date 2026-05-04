FROM node:20-bookworm-slim AS assets-builder

WORKDIR /src

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run production


FROM ghcr.io/grit-usa/akaunting-base:deploy-latest

USER root
WORKDIR /var/www/html

COPY . /var/www/html

RUN set -eux; \
    composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --optimize-autoloader; \
    mkdir -p storage/framework/{sessions,views,cache} storage/app/uploads bootstrap/cache; \
    chown -R www-data:root /var/www/html; \
    chmod -R u=rwX,g=rX,o=rX /var/www/html

COPY --from=assets-builder /src/public/js /var/www/html/public/js
COPY --from=assets-builder /src/public/css /var/www/html/public/css
COPY --from=assets-builder /src/public/mix-manifest.json /var/www/html/public/mix-manifest.json

RUN set -eux; \
    chown -R www-data:root /var/www/html; \
    chmod -R u=rwX,g=rX,o=rX /var/www/html
