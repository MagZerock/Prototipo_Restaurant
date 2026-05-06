FROM php:8.2-apache

# Habilitar el módulo de reescritura de Apache para las rutas del MVC
RUN a2enmod rewrite

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Configurar permisos para la carpeta de base de datos (JSON)
# Esto es vital para que puedas guardar pedidos, usuarios y platos
RUN chown -R www-data:www-data /var/www/html/app/database && chmod -R 775 /var/www/html/app/database

# Cambiar el DocumentRoot de Apache para que apunte a la carpeta /public
# De esta forma el index.php de la raíz será ignorado y se usará el de /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar extensiones necesarias de PHP si en el futuro usas MySQL
# RUN docker-php-ext-install pdo pdo_mysql

# Exponer el puerto 80
EXPOSE 80
