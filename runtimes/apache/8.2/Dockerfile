FROM php:8.2-apache

LABEL maintainer="Yuri Oliveira"

ARG WWWGROUP
ARG NODE_VERSION=20
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y gnupg gosu zip unzip git dnsutils fswatch

RUN curl -sSLf -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions gd bcmath pdo_mysql zip pdo_pgsql soap intl ldap \
    imagick memcached msgpack igbinary redis swoole xdebug @composer

RUN curl -fsSL "https://deb.nodesource.com/setup_$NODE_VERSION.x" | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g pnpm \
    && npm install -g bun \
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

COPY start-container /usr/local/bin/start-container
COPY php.ini /etc/php/8.2/cli/conf.d/99-peach.ini

RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

ENTRYPOINT ["start-container"]
