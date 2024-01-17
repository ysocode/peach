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

RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && npm install -g bun \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarnkey.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarnkey.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt focal-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y default-mysql-client \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd --force -g $WWWGROUP peach
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 peach

COPY php.ini /etc/php/7.4/cli/conf.d/99-peach.ini

EXPOSE 9000
