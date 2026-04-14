FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite mbstring bcmath zip

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files early to cache dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts || true

# Copy application files
COPY . ./

# Ensure storage permissions
RUN mkdir -p storage && chown -R www-data:www-data storage bootstrap/cache || true

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
