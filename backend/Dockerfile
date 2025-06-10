FROM php:8.2-apache

# Instalar extensiones PHP y otras dependencias
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    zip

# Habilitar módulos de Apache
RUN a2enmod rewrite ssl

# Crear directorio para certificados SSL
RUN mkdir -p /etc/apache2/ssl

# Generar certificado SSL autofirmado
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/ssl/localhost.key \
    -out /etc/apache2/ssl/localhost.crt \
    -subj "/C=ES/ST=Madrid/L=Madrid/O=Mi Organizacion/OU=Desarrollo/CN=localhost"

# Copiar configuración SSL
COPY docker/ssl/ssl.conf /etc/apache2/sites-available/ssl.conf

# Habilitar sitio SSL
RUN a2ensite ssl

# Copiar archivos del proyecto
COPY . /var/www/html/

# Asegurar permisos correctos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Exponer puertos HTTP y HTTPS
EXPOSE 80 443

CMD ["apache2-foreground"] 