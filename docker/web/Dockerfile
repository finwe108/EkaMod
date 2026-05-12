FROM php:8.3-apache

# Enable Apache rewrite (required for Laravel routes)
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libicu-dev libonig-dev libxml2-dev

# Install PHP extensions required by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql mbstring zip exif pcntl bcmath gd intl

# Set Apache document root to Laravel /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf