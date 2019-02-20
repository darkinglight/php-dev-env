FROM php:7.1-fpm

COPY src/ /tmp/src/

#update apt-get's source to alibaba
RUN mv /tmp/src/sources.list /etc/apt/ && mv /tmp/src/.ssh /root/.ssh

RUN apt-get update && apt-get install -y libssl-dev zlib1g-dev libmemcached-dev git

RUN docker-php-ext-install pdo_mysql mysqli sockets

#memcached extension
RUN mkdir -p /tmp/memcached \
    && tar -xf /tmp/src/memcached-3.0.4.tgz -C /tmp/memcached --strip-components=1 \
    && rm /tmp/src/memcached-3.0.4.tgz \
    && docker-php-ext-configure /tmp/memcached \
    && docker-php-ext-install /tmp/memcached \
    && rm -r /tmp/memcached

#xdebug extension
#RUN curl -fsSL 'http://pecl.php.net/get/xdebug-2.6.0beta1.tgz' -o xdebug.tar.gz \
RUN mkdir -p /tmp/xdebug \
    && tar -xf /tmp/src/xdebug.tar.gz -C /tmp/xdebug --strip-components=1 \
    && rm /tmp/src/xdebug.tar.gz \
    && docker-php-ext-configure /tmp/xdebug --enable-xdebug \
    && docker-php-ext-install /tmp/xdebug \
    && rm -r /tmp/xdebug

#redis extension
#RUN curl -fsSL 'http://pecl.php.net/get/redis-3.1.6.tgz' -o redis.tar.gz \
RUN mkdir -p /tmp/redis \
    && tar -xf /tmp/src/redis.tar.gz -C /tmp/redis --strip-components=1 \
    && rm /tmp/src/redis.tar.gz \
    && docker-php-ext-configure /tmp/redis \
    && docker-php-ext-install /tmp/redis \
    && rm -r /tmp/redis

#mongodb extension
RUN mkdir -p /tmp/mongodb \
    && tar -xf /tmp/src/mongodb-1.5.2.tgz -C /tmp/mongodb --strip-components=1 \
    && rm /tmp/src/mongodb-1.5.2.tgz \
    && docker-php-ext-configure /tmp/mongodb --with-mongodb-ssl \
    && docker-php-ext-install /tmp/mongodb \
    && rm -r /tmp/mongodb

#xhprof extension
RUN mkdir -p /tmp/tideways_xhprof \
    && tar -xf /tmp/src/php-xhprof-extension-5.0-beta2.tar.gz -C /tmp/tideways_xhprof --strip-components=1 \
    && rm /tmp/src/php-xhprof-extension-5.0-beta2.tar.gz \
    && docker-php-ext-configure /tmp/tideways_xhprof \
    && docker-php-ext-install /tmp/tideways_xhprof \
    && rm -r /tmp/tideways_xhprof

#gd
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

#install composer
#RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
#    && php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
#    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
#    && php -r "unlink('composer-setup.php');" \
#    && composer config -g repo.packagist composer https://packagist.phpcomposer.com

COPY conf.d/   /usr/local/etc/php/conf.d/

COPY test/  /data/www/test/
