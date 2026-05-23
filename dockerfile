FROM php:8.3-fpm


# Instalar dependencias del sistema (Incluye soporte para SQLite y MySQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Node.js (Versión 20 obligatoria)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo principal
WORKDIR /var/www

# Copiar archivos del proyecto
COPY . /var/www

# Ajuste de permisos para Laravel (Aseguramos que storage y bootstrap/cache tengan acceso total)
RUN chown -R $user:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Exponer puerto de PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
