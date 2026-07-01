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

# 📂 1. Primero creamos la carpeta de almacenamiento de sesiones locales (por si acaso)
RUN mkdir -p /var/www/html/storage/sessions

# 🔑 2. Ahora aplicamos chown general (incluyendo la carpeta que acabamos de crear)
RUN chown -R www-data:www-data /var/www/html

# 🔒 3. Inyección nativa y unificada en el php.ini usando Redis para el ambiente Multi-Pod
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    { \
      echo ''; \
      echo '; Custom session settings'; \
      echo 'session.save_handler = redis'; \
      echo 'session.save_path = "tcp://redis-service.eco-prod.svc.cluster.local:6379"'; \
      echo 'session.cookie_secure = On'; \
      echo 'session.cookie_httponly = On'; \
      echo 'session.cookie_samesite = Lax'; \
    } >> "$PHP_INI_DIR/php.ini"


EXPOSE 80
CMD ["apache2-foreground"]