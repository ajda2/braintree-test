FROM php:7.4-apache

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite

RUN apt-get update -y && apt-get install -y -qq --no-install-recommends \
	git \
	libwebp-dev \
	libpng-dev \
	zlib1g-dev \
	libjpeg62-turbo-dev \
	apt-transport-https \
	libfreetype6-dev \
	libmcrypt-dev \
	libzip-dev \
	zip unzip \
	wget \
	msmtp \
	vim \
	libicu-dev


RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-configure zip
RUN docker-php-ext-install -j$(nproc) iconv gd bcmath zip

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# msmtp configuration
COPY .docker/php-apache/msmtprc /etc/msmtprc


COPY .docker/php-apache/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html/temp/cache