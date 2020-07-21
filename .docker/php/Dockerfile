FROM php:7.3-fpm-alpine as web_api

RUN echo 'http://dl-cdn.alpinelinux.org/alpine/v3.6/main' >> /etc/apk/repositories && \
    echo 'http://dl-cdn.alpinelinux.org/alpine/v3.6/community' >> /etc/apk/repositories

RUN apk add --update \
    git \
    shadow \
    php7-apcu \
    php7-intl \
    libzip-dev \
    mongodb-tools \
    rabbitmq-c-dev

RUN set -xe && \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev && \
    docker-php-ext-configure zip --with-libzip=/usr/include && \
    docker-php-ext-install \
        zip \
        intl \
        opcache \
        pdo_mysql && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    pecl install amqp-1.9.4 && \
    docker-php-ext-enable amqp && \
    pecl install xdebug-beta && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

COPY install-composer.sh /usr/local/bin/install-composer

RUN set -xe && \
    apk add --no-cache --virtual .fetch-deps \
        openssl && \
    chmod +x /usr/local/bin/install-composer && \
    install-composer && \
    mv composer.phar /usr/local/bin/composer && \
    rm /usr/local/bin/install-composer && \
    composer clear-cache && \
    apk del .fetch-deps

COPY symfony.ini /usr/local/etc/php/conf.d/symfony.ini

ARG uid=1000
ARG gid=1000
RUN usermod -u $uid www-data && \
    groupmod -g $gid www-data

USER www-data

WORKDIR /var/www/app


FROM web_api as events_consumer

USER root

RUN apk add --no-cache supervisor && \
    mkdir /etc/supervisor /var/log/supervisor

COPY events_consumer.conf /etc/supervisor/events_consumer.conf
COPY supervisord.conf /etc/supervisor/supervisord.conf

ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisor/supervisord.conf"]
