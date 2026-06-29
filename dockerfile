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

# 1. Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# 2. Instalar dependencias de Node.js y COMPILAR ASSETS (Vital para Tailwind/Alpine)
RUN npm install && npm run build

# 3. Corregido: Ajuste de permisos (usando www-data directamente)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# 4. Ejecutar migraciones (para crear el archivo sqlite si no existe)
# Nota: Asegúrate de tener un .env de producción configurado en Render
RUN php artisan migrate --force

# Exponer puerto de PHP-FPM
EXPOSE 9000
#CMD ["php-fpm"]
CMD sh -c "php -S 0.0.0.0:$PORT -t public"
