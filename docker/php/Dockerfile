FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libldap2-dev \
    libpq-dev \
    libsqlite3-dev \
    libmagickwand-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libtidy-dev \
    libxslt1-dev \
    libgmp-dev \
    libbz2-dev \
    libpspell-dev \
    libsnmp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath \
        bz2 \
        calendar \
        exif \
        gd \
        gettext \
        gmp \
        intl \
        mbstring \
        mysqli \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        pcntl \
        soap \
        sockets \
        tidy \
        xsl \
        zip \
    && pecl install imagick \
    && docker-php-ext-enable imagick

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html