FROM php:8.0-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
        apt-transport-https \
        curl \
        gnupg \
        unixodbc \
        unixodbc-dev \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 \
    && pecl install sqlsrv \
    && pecl install pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv \
    && docker-php-ext-enable pdo_sqlsrv

RUN a2enmod rewrite

COPY ./ /var/www/html

RUN service apache2 restart

EXPOSE 80