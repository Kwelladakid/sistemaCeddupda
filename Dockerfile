FROM php:8.2-apache

# Instala extensões requeridas pelo Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia arquivos composer antes, para cache eficiente
COPY composer.json composer.lock /var/www/html/
WORKDIR /var/www/html
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copia o restante do projeto
COPY . /var/www/html

# Permissões ideais para storage/cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
