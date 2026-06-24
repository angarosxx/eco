# ==========================================
# ETAPA 1: Compilar assets de Tailwind con Node
# ==========================================
FROM node:20-alpine AS asset-builder
WORKDIR /app

# Copiar archivos de dependencias de Node
COPY package*.json ./
RUN npm ci

# Copiar el resto del código para que Tailwind analice las vistas
COPY . .

# Compilar el archivo CSS (Asegúrate de que este script genere public/css/app.css)
RUN npm run build

# ==========================================
# ETAPA 2: Tu servidor Apache de Producción
# ==========================================
FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd

# Habilitar el módulo de reescritura
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para apuntar a /public y permitir .htaccess (AllowOverride All)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar AllowOverride All de forma explícita para evitar errores 500/404 futuros
RUN sed -i '/<Directory ${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    || sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copiar el código fuente limpio del contexto actual
COPY . /var/www/html

# COPIAR EL CSS COMPILADO desde la etapa anterior (Evita que la carpeta vaya vacía)
COPY --from=asset-builder /app/public/css/ /var/www/html/public/css/

# Inyectar el archivo de ruteo moderno FallbackResource de forma nativa en la imagen
RUN echo "FallbackResource /index.php" > /var/www/html/public/.htaccess

# Instalar dependencias de PHP y generar autoloader optimizado
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Asegurar permisos correctos para el usuario web de Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]