# Utilisation de l'image de base PHP avec Apache
FROM php:8.2-apache

# Installation des extensions PHP requises
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie du code source de l'application
COPY . /var/www/html/

# Changement de répertoire de travail
WORKDIR /var/www/html

# Installation des dépendances avec Composer
RUN composer install --no-scripts --no-interaction

# Exposition du port 80
EXPOSE 80