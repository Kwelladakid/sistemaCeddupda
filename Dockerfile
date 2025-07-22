FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia arquivos do projeto
COPY . /var/www

# Instala dependências do projeto
RUN composer install --optimize-autoloader --no-dev

# Gera APP_KEY
RUN php artisan key:generate

# Expondo a porta padrão
EXPOSE 8000

# Comando para rodar o servidor
CMD php artisan serve --host=0.0.0.0 --port=8000
