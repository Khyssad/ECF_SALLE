# Utiliser l'image PHP officielle avec Apache
FROM php:8.3-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code source de l'application
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-interaction --optimize-autoloader

# Exposer le port 80
EXPOSE 80

# Configurer le document root d'Apache
RUN echo 'DocumentRoot /var/www/html/public' >> /etc/apache2/apache2.conf
RUN a2enmod rewrite

# Lancer Apache en premier plan
CMD ["apache2-foreground"]