FROM php:8.2-apache

# Install system and PHP dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev zip unzip libxml2-dev libpq-dev libicu-dev git curl \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set ServerName to avoid warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory to Laravel root
WORKDIR /var/www

# Copy application files
COPY . .

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend assets (Vite)
RUN npm install && npm run build

# Set correct permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Point Apache to the Laravel public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Copy and allow execution of start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Set entrypoint
CMD ["/start.sh"]

EXPOSE 80
