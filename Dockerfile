FROM php:7.4.16-fpm-alpine3.13 as prod

RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY    composer.json composer.lock /var/www/
RUN composer install --no-dev --optimize-autoloader

COPY    symfony.lock /var/www/
COPY    src /var/www/src
COPY    config /var/www/config
COPY    public /var/www/public
COPY    bin /var/www/bin

CMD php-fpm
EXPOSE 9000

FROM prod as dev

COPY    behat.yml /var/www/
COPY    features /var/www/features
COPY    tests /var/www/tests

RUN composer install

CMD php-fpm