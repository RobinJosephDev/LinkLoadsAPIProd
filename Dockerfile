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

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install and build React (if needed)
RUN npm install && npm run build

# Expose port
EXPOSE 10000

# Start Laravel after clearing and caching config at runtime
CMD php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan cache:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan serve --host=0.0.0.0 --port=10000
