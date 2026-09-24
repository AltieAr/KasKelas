FROM php:8.4.25-cli

# Install dependencies yang dibutuhkan
RUN apt-get update && apt-get install -y git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install package Laravel
RUN composer install --no-dev --optimize-autoloader

# Atur permission folder cache & storage
RUN chmod -R 775 storage bootstrap/cache

ENV PORT=10000
EXPOSE ${PORT}

# Jalankan config cache, auto-migrate, dan jalankan server
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT}
