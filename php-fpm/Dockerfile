FROM php:7.2.2-fpm

RUN apt-get update && \
	apt-get install -y mysql-client && \
	docker-php-ext-install pdo_mysql && \
    apt-get install -y --no-install-recommends unzip librabbitmq4 librabbitmq-dev ;\
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false ;\
    rm -rf /var/lib/apt/lists/* && \
    apt-get update \
    && apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install sockets \
    && docker-php-ext-enable amqp

WORKDIR "/application"