FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y git unzip libzip-dev && docker-php-ext-install zip && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

# Default command will be overridden by docker-compose
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]