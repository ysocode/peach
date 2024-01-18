FROM php:7.4-fpm

LABEL maintainer="Yuri Oliveira"

ARG WWWGROUP
ARG NODE_VERSION=14
ARG POSTGRES_VERSION=13

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu zip unzip git dnsutils fswatch libfreetype-dev libjpeg62-turbo-dev libmemcached-dev \
    libpng-dev libwebp-dev libgif-dev libzip-dev libldap2-dev libxml2-dev libicu-dev libpq-dev libmagickwand-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd bcmath pdo_mysql zip pdo_pgsql soap intl ldap

RUN pecl install imagick-3.7.0 && pecl install memcached-3.2.0 && pecl install msgpack-2.2.0 && pecl install igbinary-3.2.15 \
    && pecl install redis-6.0.2 && pecl install swoole-4.8.13 && pecl install xdebug-3.1.6 \
    && docker-php-ext-enable imagick memcached msgpack igbinary redis swoole xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN curl -fsSL "https://deb.nodesource.com/setup_$NODE_VERSION.x" | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g yarn

RUN apt-get update \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION

RUN apt-get update \
    && apt-get update \
    && apt-get install -y default-mysql-client

RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd --force -g $WWWGROUP peach
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 peach

COPY php.ini /etc/php/7.4/cli/conf.d/99-peach.ini

EXPOSE 9000
