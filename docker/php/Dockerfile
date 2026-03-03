FROM php:8.2-fpm

ENV HTTP_PROXY=http://10.10.254.219:3128/
ENV HTTPS_PROXY=http://10.10.254.219:3128/
ENV NO_PROXY=10.,192.168.,.sfnet,wpad,127.0.0.1,localhost

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Instalar Composer directamente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
