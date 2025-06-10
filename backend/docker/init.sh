#!/bin/bash

# Esperar a que MySQL esté listo
echo "Esperando a que MySQL esté disponible..."
until mysql -h mysql -u sail -ppassword -e "SELECT 1"; do
  echo "MySQL no está disponible aún - esperando..."
  sleep 2
done

# Inicializar la base de datos
echo "Inicializando la base de datos..."
mysql -h mysql -u sail -ppassword laravel < /var/www/html/init.sql

# Iniciar Apache en primer plano
echo "Iniciando Apache..."
apache2-foreground 