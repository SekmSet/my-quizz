FROM php:7.4.5-apache

RUN apt update && apt install -y unzip

RUN docker-php-ext-install mysqli pdo pdo_mysql opcache
COPY ./docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html/
COPY ./ /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN chmod -R 777 var
RUN a2enmod rewrite

