FROM php:7.4.16-fpm-alpine3.13 as prod

RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY    composer.json /var/www
COPY    composer.lock /var/www

WORKDIR /var/www

CMD composer install  --production ;  php-fpm

EXPOSE 9000

FROM prod as dev

CMD compose install --dev ; php-fpm