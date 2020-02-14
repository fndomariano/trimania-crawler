FROM php:7.2-apache

MAINTAINER Fernando Mariano <fernando.mar16@gmail.com>

RUN apt-get update && apt-get install -y libmcrypt-dev libicu-dev libssl-dev git libpq-dev zlib1g-dev curl

#Install PHP Extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN pecl install mcrypt-1.0.1
RUN docker-php-ext-enable mcrypt

#Install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Enable Module Rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html