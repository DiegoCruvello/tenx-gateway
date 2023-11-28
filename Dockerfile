# Use a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala as extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar extensões adicionais que podem ser necessárias
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
# Configurar o Apache para apontar para o diretório public do Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/|/var/www/html/public|g' /etc/apache2/apache2.conf

# Habilitar mod_rewrite para URLs amigáveis do Laravel
RUN a2enmod rewrite

# Expõe a porta 80
EXPOSE 80
