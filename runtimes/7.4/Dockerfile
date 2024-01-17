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
    && apt-get install -y gnupg gosu zip unzip git dnsutils fswatch libfreetype-dev libjpeg62-turbo-dev \
    libpng-dev libwebp-dev libgif-dev libzip-dev libldap2-dev libxml2-dev libicu-dev libpq-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd bcmath pdo_mysql zip pdo_pgsql soap intl ldap

# As dependencias instaladas com pecl requerem versoes especificas, por isso foram removidas
# ao adicionalas novamente sera necessario instalar o pacote apt libmagickwand-dev para extensao imagick

# RUN pecl install imagick && pecl install memcached && pecl install msgpack && pecl install igbinary \
#     && pecl install redis && pecl install swoole && pecl install xdebug \
#     && docker-php-ext-enable imagick memcached msgpack igbinary redis swoole xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN curl -fsSL "https://deb.nodesource.com/setup_$NODE_VERSION.x" | bash - \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g yarn

RUN echo "deb https://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add - \
    && apt-get update \
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
