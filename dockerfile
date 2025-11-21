FROM php:8.2-apache

# Active mod_rewrite pour Apache
RUN a2enmod rewrite

# Permet à Apache de lire le .htaccess
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copie le code dans le conteneur
COPY . /var/www/html

# Donne les droits à Apache
RUN chown -R www-data:www-data /var/www/html