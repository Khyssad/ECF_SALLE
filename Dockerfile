# Utiliser une image de PHP avec Apache
FROM php:8.3-apache

# Installer les extensions requises
RUN docker-php-ext-install pdo pdo_mysql

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances Symfony
RUN composer install

# Exposer le port 80
EXPOSE 80

# Démarrer le serveur Symfony
CMD ["php", "bin/console", "serve:start", "127.0.0.1:80"]