# Utiliser l'image de PHP avec Apache
FROM php:8.3-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copier le code source de l'application
COPY . /var/www/html/

# Configuration de l'Apache
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html/

# Installer les dépendances de Composer (si nécessaire)
COPY composer.json composer.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Exposer le port 80
EXPOSE 80