# Set base image to PHP 8.2 with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    nginx \
    libpq-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage

# Copy Nginx config
COPY ./deploy/render-nginx.conf /etc/nginx/conf.d/default.conf

# Copy Supervisor config to run both services
COPY ./deploy/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose web port
EXPOSE 8080

# Start both Nginx and PHP-FPM using Supervisor
CMD ["/usr/bin/supervisord"]
