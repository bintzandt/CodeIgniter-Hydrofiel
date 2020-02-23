FROM php:7.4.1-apache-buster
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# Enable headers
RUN a2enmod headers

# Restart Apache
RUN service apache2 restart
