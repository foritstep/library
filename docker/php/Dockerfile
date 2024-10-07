FROM alpine:3.20

RUN version=83 && \
   apk --update add \
        php83 \
        php83-bcmath \
        php83-dom \
        php83-ctype \
        php83-curl \
        php83-fileinfo \
        php83-fpm \
        php83-gd \
        php83-iconv \
        php83-intl \
        php83-json \
        php83-mbstring \
        php83-opcache \
        php83-openssl \
        php83-pdo \
        php83-pdo_sqlite \
        php83-pdo_pgsql \
        php83-pdo_mysql \
        php83-phar \
        php83-posix \
        php83-simplexml \
        php83-session \
        php83-soap \
        php83-tokenizer \
        php83-xml \
        php83-xmlreader \
        php83-xmlwriter \
        php83-zip \
        composer \
        shadow \
    && groupmod -g 1000 www-data \
    && useradd -g www-data -u 1000 www-data -m \
    && rm -rf /var/cache/apk/* \
    && sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/;s/user = nobody/user = www-data/;s/group = nobody/group = www-data/' /etc/php83/php-fpm.d/www.conf \
    && apk del shadow

EXPOSE 9000

WORKDIR /var/www/html

CMD ["php-fpm83", "-F"]
