FROM php:8.2-apache

# Instala dependencias y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Habilita módulos de Apache
RUN a2enmod rewrite headers

# Copia los archivos de la aplicación
COPY . /var/www/html/

# Configuración de PHP para producción (opcional)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Puerto expuesto
EXPOSE 80

CMD ["apache2-foreground"]