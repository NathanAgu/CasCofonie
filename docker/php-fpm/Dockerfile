FROM php:8.2-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends iproute2 zip zlib1g-dev libzip-dev libxml2-dev libpng-dev libghc-curl-dev \
    libldb-dev libldap2-dev default-mysql-client libpq-dev -yqq && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) pdo pdo_mysql zip xml intl gd curl bcmath opcache ldap && \
    docker-php-ext-enable apcu pdo_mysql sodium ldap && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install Xdebug
RUN pecl install xdebug-3.3.2 && docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Cleanup.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
