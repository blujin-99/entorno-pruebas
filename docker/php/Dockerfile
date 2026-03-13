FROM php:8.3-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libicu-dev libxml2-dev \
    && docker-php-ext-install intl pdo_mysql zip

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
 -- --install-dir=/usr/local/bin --filename=composer

# Permitir .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
