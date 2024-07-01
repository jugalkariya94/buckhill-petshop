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

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* ./

# install
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction


COPY --link . ./

# Generate composer autoload files
RUN composer dump-autoload --optimize

# create a new linux user group called 'developer' with an arbitrary group id of '1001'
RUN groupadd -g 1000 developer

# create a new user called developer and add it to this group
RUN useradd -u 1000 -g developer developer

# change the owner and group of the current working directory to developer
COPY --chown=developer:developer . /app

# run all subsequent processes as this user
USER developer


ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
