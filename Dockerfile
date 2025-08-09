FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    git \
    curl \
    nodejs \
    npm \
 && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy everything
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install and build React (if needed)
RUN npm install && npm run build

# Cache Laravel config
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Expose port
EXPOSE 10000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
