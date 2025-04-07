FROM php:8.2-apache

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install dependencies
RUN apt-get update && apt-get install -y \
    && docker-php-ext-install pdo_mysql

# Copy files
COPY . /var/www/html/

# Permissions
RUN chmod -R 755 /var/www/html
