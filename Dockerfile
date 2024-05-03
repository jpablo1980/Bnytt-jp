FROM php:7.4-fpm AS dependencies
WORKDIR /usr/share/nginx

RUN apt-get update \
 && apt-get install -y \
      "bash" \
      "ca-certificates" \
      "curl" \
      "git" \
      "less" \
      "nginx" \
      "wget" \
      "procps" \
      "libzip-dev" \
      "unzip" \
      "libfreetype6-dev" \
      "libjpeg62-turbo-dev" \
      "libpng-dev" \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql zip gd exif

RUN wget https://phar.phpunit.de/phpunit-9.5.8.phar \
 && chmod +x phpunit-9.5.8.phar \
 && mv phpunit-9.5.8.phar /usr/local/bin/phpunit \
 && phpunit --version

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php \
 && php -r "unlink('composer-setup.php');" \
 && mv composer.phar /bin/composer

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
 && rm -f /usr/local/etc/php-fpm.d/zz-docker.conf /usr/local/etc/php-fpm.d/www.conf.default

RUN mkdir -p /var/tmp/nginx \
 && chown www-data:www-data -R /var/tmp/nginx \
 && mkdir -p /run/php-fpm \
 && chown www-data:www-data -R /run/php-fpm \
 && mkdir -p /run/nginx \
 && chown www-data:www-data -R /run/nginx \
 && mkdir -p /var/lib/nginx \
 && chown www-data:www-data -R /var/lib/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY docker/php-pool.conf "/usr/local/etc/php-fpm.d/www.conf"
COPY docker/php-conf.conf "$PHP_INI_DIR/conf.d/custom.ini"

COPY docker/entrypoint.sh /entrypoint.sh
ENTRYPOINT [ "/entrypoint.sh" ]

FROM dependencies AS build
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY . /usr/share/nginx
RUN --mount=type=ssh composer install --optimize-autoloader --apcu-autoloader
