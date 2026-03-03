FROM php:8.2-fpm

# Instalar dependencias necesarias para Symfony + Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
