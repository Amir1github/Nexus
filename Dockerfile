# Используем официальный PHP-образ с Apache
FROM php:8.1-apache

# Копируем все файлы из текущей директории в папку сайта
COPY . /var/www/html/

# Включаем mod_rewrite (часто нужен для .htaccess)
RUN a2enmod rewrite

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Открываем порт 80
EXPOSE 80
