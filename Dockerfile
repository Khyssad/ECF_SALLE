<<<<<<< HEAD
# Utiliser l'image de PHP avec Apache
FROM php:8.3-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql
=======
# Utiliser l'image PHP officielle avec Apache
FROM php:8.2-apache

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html
>>>>>>> c2ca0c2 (Updated and modify docker)

# Copier le code source de l'application
COPY . /var/www/html/

<<<<<<< HEAD
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
=======
# Installer les dépendances de Symfony
RUN composer install

# Exposer le port 80
EXPOSE 80

# Assurer que Apache est en cours d'exécution
CMD ["apache2-foreground"]
>>>>>>> c2ca0c2 (Updated and modify docker)
