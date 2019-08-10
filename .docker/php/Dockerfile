FROM php:7.3-fpm-alpine

RUN apk add --update \
    php7-apcu \
    php7-intl \
    libzip-dev \
    mongodb-tools

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

WORKDIR /var/www/app
