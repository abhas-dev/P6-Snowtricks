FROM php:8.0.12-apache-bullseye

WORKDIR /var/www/app

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Setup context
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS="0"

# Quelques library necessaires
RUN apt-get update \
    &&  apt-get install -y --no-install-recommends \
    locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip

# les locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
    &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    &&  locale-gen

# On télécharge et deplace composer
RUN  curl -sS https://getcomposer.org/installer | php -- \
    &&  mv composer.phar /usr/local/bin/composer 

# On télécharge et deplace le symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony/bin/symfony /usr/local/bin \
    \ 
    &&  docker-php-ext-configure \
    intl \
    &&  docker-php-ext-install \
    pdo pdo_mysql opcache intl zip calendar dom mbstring gd xsl \
    \
    &&  pecl install apcu && docker-php-ext-enable apcu

RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install --global yarn


# On créé un utilisateur avec le même gid/uid que le local
RUN addgroup --system redpanda --gid 1000 && adduser --system redpanda --uid 1000 --ingroup redpanda
RUN #usermod -u 1000 www-data
