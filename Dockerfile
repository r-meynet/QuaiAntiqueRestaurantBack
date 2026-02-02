FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration d'Apache
RUN a2enmod rewrite
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copie du projet
WORKDIR /var/www/html
COPY . .

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Permissions
RUN chown -R www-data:www-data /var/www/html/var

EXPOSE 80