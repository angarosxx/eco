FROM php:8.2-apache

# 1. Instalamos dependencias del sistema, herramientas de compilación y extensiones
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    build-essential \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    # 🧼 Limpieza inmediata para reducir el peso de la capa de la imagen
    && apt-get purge -y --auto-remove build-essential $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -i '/<Directory ${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN chown -R www-data:www-data /var/www/html
RUN mkdir -p /var/www/html/storage/sessions

# 🔒 Inyección nativa de cookies seguras y HttpOnly a nivel de entorno PHP
RUN echo "session.cookie_secure = On" >> /usr/local/etc/php/conf.d/docker-php-sessions.ini && \
    echo "session.cookie_httponly = On" >> /usr/local/etc/php/conf.d/docker-php-sessions.ini && \
    echo "session.cookie_samesite = Lax" >> /usr/local/etc/php/conf.d/docker-php-sessions.ini

EXPOSE 80
CMD ["apache2-foreground"]