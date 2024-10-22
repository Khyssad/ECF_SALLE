# Utilise l'image officielle PHP avec Apache intégré
FROM php:8.3-apache

# Installation des dépendances requises pour Symfony et MySQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Active les modules Apache nécessaires
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copie du code source Symfony
COPY . /var/www/html

# Installation des dépendances Symfony
RUN composer install

# Réglage des permissions
RUN chown -R www-data:www-data /var/www/html/var
RUN chmod -R 775 /var/www/html/var

# Copie du fichier de configuration Apache pour Symfony
COPY ./docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Exposer le port 8000 pour l'application Symfony
EXPOSE 8000