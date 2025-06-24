FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev zip unzip libxml2-dev libpq-dev libicu-dev git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www

# Copy Laravel project into container
COPY . .

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Set Apache to use Laravel's public/ directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Optional: suppress ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose web server port
EXPOSE 80
