FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev zip unzip libxml2-dev libpq-dev libicu-dev git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set ServerName to avoid warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory to Laravel root
WORKDIR /var/www

# Copy Laravel app into container
COPY . .

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Set Apache to serve from public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Set Apache to serve from public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Run migrations and seeders (temporary line!)
# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Set Apache to serve from public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Optional: set ServerName to remove warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Add start script
COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]

EXPOSE 80
