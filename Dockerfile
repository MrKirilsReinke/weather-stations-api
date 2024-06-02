FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    git \
    unzip \
    && docker-php-ext-install zip gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www

COPY . /var/www/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader

RUN sed -i 's|/var/www/html|/var/www/public|g' /etc/apache2/sites-available/000-default.conf

RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

CMD ["apache2-foreground"]

