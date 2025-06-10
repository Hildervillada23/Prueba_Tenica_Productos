#!/bin/bash

echo "=== Reconstruyendo contenedores Docker ==="
echo "Deteniendo contenedores actuales..."
docker-compose down

echo "Limpiando caché de Docker..."
docker system prune -f

echo "Reconstruyendo contenedores..."
docker-compose build --no-cache

echo "Iniciando contenedores..."
docker-compose up -d

echo "Esperando a que los servicios estén listos..."
sleep 10

echo "Verificando estado de los contenedores..."
docker-compose ps

echo "Inicialización de la base de datos..."
docker-compose exec backend php setup-db.php

echo "=== Contenedores reconstruidos correctamente ==="
echo "Puedes acceder a:"
echo "- Frontend: http://localhost:3000"
echo "- Backend API: http://localhost:8000/api/products"
echo "- Interfaz HTML: http://localhost:8000/test-ui.html" 