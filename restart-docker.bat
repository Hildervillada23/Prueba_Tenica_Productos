@echo off
SETLOCAL EnableDelayedExpansion

echo === Reconstruyendo contenedores Docker ===
echo Deteniendo contenedores actuales...
docker-compose down

echo Limpiando cache de Docker...
docker system prune -f

echo Reconstruyendo contenedores...
docker-compose build --no-cache

echo Iniciando contenedores...
docker-compose up -d

echo Esperando a que los servicios esten listos...
timeout /t 10 /nobreak >nul

echo Verificando estado de los contenedores...
docker-compose ps

echo Inicializacion de la base de datos...
docker-compose exec backend php setup-db.php

echo === Contenedores reconstruidos correctamente ===
echo Puedes acceder a:
echo - Frontend: http://localhost:3000
echo - Backend API: http://localhost:8000/api/products
echo - Interfaz HTML: http://localhost:8000/test-ui.html

ENDLOCAL
pause 