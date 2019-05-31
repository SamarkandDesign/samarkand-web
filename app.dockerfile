FROM php:7.1-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
  mysql-client libmagickwand-dev --no-install-recommends

RUN pecl install imagick
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install pdo_mysql gd exif
RUN docker-php-ext-enable imagick
