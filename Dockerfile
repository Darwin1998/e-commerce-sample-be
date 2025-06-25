# Use PHP with Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libonig-dev libxml2-dev libpq-dev libicu-dev \
    git curl libpng-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml intl

# Enable Apache rewrite module
RUN a2enmod rewrite

# Fix Apache DocumentRoot to point to Laravel's public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/public|g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www

# Copy composer from composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app source
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node and build assets
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install && \
    npm run build

# Fix permissions after build
RUN chown -R www-data:www-data /var/www

# Add and run startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port
EXPOSE 80

# Start the server
CMD ["/start.sh"]
