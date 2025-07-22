FROM php:8.2-apache

# Instala extensões requeridas pelo Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia seu projeto para a imagem
COPY . /var/www/html

# Permite o Apache acessar os arquivos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Porta padrão Apache
EXPOSE 80

# Comando padrão do Apache
CMD ["apache2-foreground"]
