FROM php:alpine

RUN apk update && \
    apk add curl \
    php-openssl \
    php-pdo \
    php-json \
    php-phar \
    php-dom \
    php-curl \
    php-mbstring \
    php-tokenizer \
    php-xml \
    php-session \
    php-ctype \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./ /app
RUN mkdir /app/vendor
EXPOSE 8000

RUN adduser --disabled-password laravel-user

RUN ["chmod", "+x", "/app/init.sh"]
RUN ["chmod", "-R", "775", "/app", "/app/vendor", "/app/storage", "/app/bootstrap/cache"]
RUN ["chown", "-R", "laravel-user:laravel-user", "/app/vendor", "/app/storage", "/app/bootstrap/cache"]

USER laravel-user

WORKDIR /app
ENTRYPOINT ["/bin/sh", "/app/init.sh"]

