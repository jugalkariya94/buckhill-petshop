FROM dunglas/frankenphp

WORKDIR /app

RUN install-php-extensions \
    @composer \
    pdo_mysql \
    gd \
    intl \
    imap \
    bcmath \
    redis \
    exif \
    mysqli \
    pcntl \
    zip
    # Add other PHP extensions here...

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /app

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* ./

# install
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

# Generate composer autoload files
RUN composer dump-autoload --optimize

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
