# Utilizamos una imagen oficial de PHP con Apache como base
FROM php:8.0-apache

# Instalamos las dependencias necesarias para la conexión con SQL Server
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

# Habilita el módulo de Apache mod_rewrite
RUN a2enmod rewrite

# Copiamos los archivos del sitio web a la carpeta del servidor de Apache
COPY ./ /var/www/html

# Reinicia el servidor Apache para aplicar los cambios
RUN service apache2 restart

# Exponemos el puerto 80 para acceder al servidor web
EXPOSE 80