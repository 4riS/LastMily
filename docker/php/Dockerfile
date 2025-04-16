FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libicu-dev \
    libzip-dev \
    zip \
    libonig-dev \
    libpq-dev \
    libxml2-dev \
    librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip opcache && docker-php-ext-enable amqp mysqli

RUN apachectl restart

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer