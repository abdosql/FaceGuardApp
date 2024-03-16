FROM lavoweb/php-8.3

# Update package lists and install necessary dependencies
RUN apt-get update \
    && apt-get install -y --fix-missing \
        git \
        unzip \
        zip \
        nano \
        cron \
        libxslt-dev \
        librabbitmq-dev \
        libssl-dev \
        nodejs \
        npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install and configure PHP extensions
RUN mkdir -p /usr/src/php/ext/apcu \
    && curl -fsSL https://pecl.php.net/get/apcu | tar xvz -C "/usr/src/php/ext/apcu" --strip 1 \
    && docker-php-ext-install apcu xsl

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install PHP extensions using the mlocati/php-extension-installer script
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd pdo_mysql zip opcache

WORKDIR /var/www/html

EXPOSE 8585