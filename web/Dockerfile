FROM php:8.2-apache

# 1. Install production system dependencies and clean up caches to keep it light
# Added 'git' here because Composer often needs it to clone packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# 2. Configure and install core extensions (PDO MySQL & GD for imagery uploads)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql gd

# 3. Enable Apache's mod_rewrite engine natively (crucial for API/MVC routing paths)
RUN a2enmod rewrite

# 4. Point Apache's DocumentRoot right into your public directory framework
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Set up the working execution workspace
WORKDIR /var/www/html

# -------------------------------------------------------------
# 🔥 NEW: COMPOSER LAYER INJECTION
# -------------------------------------------------------------
# Pull the latest official Composer binary directly into your build environment
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only your composer files first (this allows Docker to cache vendor dependencies)
COPY web/composer.json web/composer.lock* ./

# Run Composer installation inside the container working directory
RUN composer install --no-dev --optimize-autoloader --no-interaction
# -------------------------------------------------------------

# 6. Copy the application codes matching your clean workspace paths
COPY . .

# 7. Set correct web server execution permissions for user assets
# This now protects the freshly built vendor directory as well!
RUN chown -R www-data:www-data /var/www/html

# Apache exposes port 80 natively out-of-the-box
EXPOSE 80

CMD ["apache2-foreground"]