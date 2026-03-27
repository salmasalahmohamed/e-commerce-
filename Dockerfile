FROM php:8.2-cli

WORKDIR /app

# Install system deps
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction

# Start Laravel
CMD php artisan serve --host=0.0.0.0 --port=$PORT
