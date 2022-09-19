FROM debian:bookworm

WORKDIR /var/www/html

RUN apt-get update && \
        apt-get upgrade -y && \
        apt-get install -y curl busybox && \
        apt-get install -y php php-bcmath php-ctype php-curl php-dom php-fileinfo php-intl php-gd php-json php-mbstring php-pdo php-tokenizer php-xml php-zip && \
        apt-get autoremove -y && \
        rm -rf /var/lib/apt/lists/*

RUN rm -rf /var/www/html/* && \
        sed -i "/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/" /etc/apache2/apache2.conf && \
        ln -sf /proc/self/fd/1 /var/log/apache2/access.log && \
        ln -sf /proc/self/fd/1 /var/log/apache2/error.log && \
        a2enmod rewrite

RUN curl -sL $(curl -s "https://api.github.com/repos/akaunting/akaunting/releases/latest" | grep "http.*-Stable.zip" | awk '{print $2}' | sed 's|[\"\,]*||g') | busybox unzip - && \
        chown -R www-data:www-data .

CMD ["/bin/bash", "/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
