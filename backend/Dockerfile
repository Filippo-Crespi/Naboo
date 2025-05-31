FROM php:8.2-apache

# Abilita mod_rewrite per Apache
RUN a2enmod rewrite

# Installa estensione PDO e PDO_MYSQL per PHP
RUN docker-php-ext-install pdo pdo_mysql

# Copia i file PHP nel container
COPY . /var/www/html/

# Imposta permessi corretti
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
