FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite

COPY .docker/php-apache-debug/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y && apt-get install -y -qq git \
	libpng-dev\
	zlib1g-dev \
	libjpeg62-turbo-dev \
	apt-transport-https \
	libfreetype6-dev \
	libmcrypt-dev \
	zip unzip \
	wget \
	msmtp \
	vim \
	libicu-dev


RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) iconv gd bcmath

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# msmtp configuration
COPY .docker/php-apache/msmtprc /etc/msmtprc

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug
COPY .docker/php-apache-debug/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN mkdir -m 777 -p /var/www/html/temp/cache