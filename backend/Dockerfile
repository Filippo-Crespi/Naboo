FROM php:8.2-apache

# Abilita mod_rewrite per Apache (necessario per routing dinamico)
RUN a2enmod rewrite

# ✅ Installa estensione PDO per MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copia eventuali file di configurazione Apache personalizzati (es. virtual host)
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copia tutto il codice nella cartella servita da Apache
# (verrà sovrascritto dal volume in fase di sviluppo)
COPY . /var/www/html/

# Dai i permessi corretti alla cartella (se serve)
RUN chown -R www-data:www-data /var/www/html

# Espone la porta 80 del container (mappata a 8080 nel docker-compose)
EXPOSE 80
