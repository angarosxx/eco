FROM alpine:3.18

# Install Nginx, PHP 8.2, and critical extensions for database & image handling
RUN apk add --no-cache \
    nginx \
    php82 \
    php82-fpm \
    php82-mysqli \
    php82-pdo \
    php82-pdo_mysql \
    php82-json \
    php82-openssl \
    php82-curl \
    php82-zlib \
    php82-xml \
    php82-phar \
    php82-intl \
    php82-dom \
    php82-xmlwriter \
    php82-mbstring \
    php82-gd \
    supervisor

# Ensure symlinks for standard php execution names
RUN ln -s /usr/bin/php82 /usr/bin/php

# Configure Nginx & PHP-FPM
COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/fpm.conf /etc/php82/php-fpm.d/www.conf
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# CORRECCIÓN: Copia el contenido actual (que ya es la carpeta web) al WORKDIR
COPY . .

# Setup proper folder permissions for public uploads later
# (Aseguramos que todo /var/www/html sea de nginx para evitar bloqueos de PHP)
RUN mkdir -p /var/www/html/public/uploads && \
    chown -R nginx:nginx /var/www/html && \
    chmod -R 755 /var/www/html/public/uploads

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]