FROM caddy:latest

ARG WWWGROUP

RUN apk add --no-cache shadow

RUN mkdir -p /var/www/html

RUN groupadd --force -g $WWWGROUP peach
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 peach

RUN chown -R 1000:1000 /var/www/html 2>/dev/null || true