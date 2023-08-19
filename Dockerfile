# Stage 1: Build the app
FROM php:8.2-fpm AS builder

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev libpq-dev zip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY app/composer.json app/composer.lock ./
RUN composer install --prefer-dist --no-scripts --no-progress --no-suggest --no-interaction

# Stage 2: Final image
FROM php:8.2-fpm

# Copy necessary files from the builder stage
COPY --from=builder /usr/bin /usr/bin
COPY --from=builder /usr/local/bin /usr/local/bin
COPY --from=builder /var/www/html /var/www/html

# Install necessary tools
RUN apt-get update && apt-get install -y wget

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    cp /root/.symfony5/bin/symfony /usr/local/bin/symfony
