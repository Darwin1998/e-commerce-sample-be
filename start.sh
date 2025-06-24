#!/bin/bash

# Run Laravel setup
php artisan config:cache
php artisan migrate --force
php artisan db:seed --force

# Start Apache
apache2-foreground
