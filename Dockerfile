FROM composer:latest as builder

WORKDIR /app

ENV APP_ENV=prod
ENV APP_DEBUG=0

COPY bin/ ./bin/
COPY config/ ./config/
COPY data/ ./data/
COPY public/ ./public/
COPY src/ ./src/
COPY composer.* symfony.lock ./

RUN composer install --no-dev --optimize-autoloader
RUN bin/console cache:warmup

FROM alpine:latest

WORKDIR /var/www/html

RUN apk add --no-cache \
  curl \
  nginx \
  php83 \
  php83-ctype \
  php83-curl \
  php83-dom \
  php83-fileinfo \
  php83-fpm \
  php83-intl \
  php83-mbstring \
  php83-opcache \
  php83-openssl \
  php83-phar \
  php83-tokenizer \
  php83-xml \
  php83-xmlreader \
  php83-xmlwriter \
  supervisor

ARG WEBHOOK_SECRET

ENV APP_DEBUG 0
ENV APP_ENV=prod
ENV WEBHOOK_SECRET=${WEBHOOK_SECRET}
ENV PHP_INI_DIR /etc/php83

COPY infra/nginx.conf /etc/nginx/nginx.conf
COPY infra/conf.d /etc/nginx/conf.d/
COPY infra/fpm-pool.conf ${PHP_INI_DIR}/php-fpm.d/www.conf
COPY infra/php.ini ${PHP_INI_DIR}/conf.d/custom.ini
COPY infra/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx
RUN ln -s /usr/bin/php83 /usr/bin/php

USER nobody

COPY --from=builder --chown=nobody /app /var/www/html

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping || exit 1
