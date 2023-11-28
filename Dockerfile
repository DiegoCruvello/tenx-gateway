FROM php:8.2-cli

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
        curl

RUN docker-php-ext-install mbstring exif pcntl bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 8000
