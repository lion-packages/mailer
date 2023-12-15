FROM php:8.2-apache

RUN useradd -m lion && echo 'lion:lion' | chpasswd && usermod -aG sudo lion && usermod -s /bin/bash lion

RUN apt-get update && apt-get install -y sudo nano sendmail libzip-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite

CMD composer install && php -S 0.0.0.0:8000