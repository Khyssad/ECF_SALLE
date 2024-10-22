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

# Copier le code source de l'application
COPY . .

# Installer les dépendances de Symfony
RUN composer install

# Exposer le port 80
EXPOSE 80

# Assurer que Apache est en cours d'exécution
CMD ["apache2-foreground"]