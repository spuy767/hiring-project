FROM mileschou/phalcon:7.3-apache

RUN apt-get update && apt-get install -y git zlib1g zlib1g-dev libzip-dev
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
