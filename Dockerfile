FROM php:8.2-apache

RUN a2enmod rewrite

COPY . /var/www/html/

RUN mkdir -p /var/www/html/app/database && \
    chown -R www-data:www-data /var/www/html/app/database && \
    chmod -R 775 /var/www/html/app/database

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80