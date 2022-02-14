FROM php:8.0-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get upgrade -y
RUN apt-get update -y

# Used for image compression
RUN apt-get install -y jpegoptim optipng

RUN apt-get install -y libicu-dev && docker-php-ext-install intl opcache
RUN apt-get install -y libzip-dev && docker-php-ext-install zip
RUN apt-get install -y libpng-dev && docker-php-ext-install gd
RUN apt-get install -y libmcrypt-dev
RUN pecl install mcrypt-1.0.4 && docker-php-ext-enable mcrypt

RUN apt-get update && apt-get install -y libicu-dev && docker-php-ext-install intl opcache

RUN docker-php-ext-install pdo_mysql bcmath iconv

RUN echo 'date.timezone = "Europe/Paris"' > /usr/local/etc/php/conf.d/tzone.ini
USER root
