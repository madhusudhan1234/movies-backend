FROM php:8.5-fpm

ARG UID=1000
ARG GID=1000

ENV UID=${UID}
ENV GID=${GID}
ENV TZ=UTC

# Set Timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y \
        supervisor unzip \
        libgmp-dev \
        autoconf zlib1g-dev \
        libpq-dev libzip-dev \
        libonig-dev \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure exif --enable-exif\
    && docker-php-ext-install -j$(nproc) gmp pdo pdo_mysql pdo_pgsql pgsql zip pcntl bcmath gd exif mbstring \
    && MAKEFLAGS="-j $(nproc)" pecl install redis xdebug \
    && strip --strip-debug /usr/local/lib/php/extensions/*/redis.so \
    && docker-php-ext-enable redis xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Configure Xdebug
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN groupadd --force -g ${GID} laravel
RUN useradd -ms /bin/bash --no-user-group -g ${GID} -u ${UID} laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www

# Set permissions
RUN chown -R laravel:laravel /var/www
USER laravel

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
