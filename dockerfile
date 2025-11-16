FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libzip-dev libxml2-dev libpng-dev libpq-dev \
    && docker-php-ext-install intl pdo pdo_mysql pdo_pgsql zip opcache \
    && docker-php-ext-enable opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
