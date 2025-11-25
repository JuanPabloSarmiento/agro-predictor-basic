FROM php:8.1-fpm

WORKDIR /var/www/html

# Install mysqli/pdo extensions for MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Copy application code
COPY ../app /var/www/html

# Ensure permissions (not ideal for production)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
