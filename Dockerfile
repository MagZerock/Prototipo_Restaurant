FROM php:8.2-apache

# 1. Instalación de dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && a2enmod rewrite

# 2. Instalación de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Copiar archivos y ejecutar Composer
COPY . /var/www/html/

# Ajuste de permisos antes de instalar dependencias
RUN chown -R www-data:www-data /var/www/html

USER www-data
RUN composer install --no-interaction --optimize-autoloader --no-dev
USER root

# 4. Configuración de Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Asegurar permisos en carpetas de caché/logs si usas frameworks como Slim o Laravel
RUN chmod -R 775 /var/www/html/storage 2>/dev/null || true

EXPOSE 80