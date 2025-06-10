@echo off
SETLOCAL EnableDelayedExpansion

echo === Mi Catalogo de Productos - Script de Inicio ===
echo Este script verificara e iniciara todos los componentes necesarios.
echo.

REM Verificar si Docker está instalado
WHERE docker >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo ERROR: Docker no esta instalado. Por favor, instalalo primero.
    echo Puedes descargarlo desde: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

REM Verificar si docker-compose está instalado
WHERE docker-compose >nul 2>nul
IF %ERRORLEVEL% NEQ 0 (
    echo ERROR: Docker Compose no esta instalado. Por favor, instalalo primero.
    echo Normalmente viene incluido con Docker Desktop.
    pause
    exit /b 1
)

echo Verificando si hay contenedores en ejecucion...
docker ps | findstr "mi-catalogo-productos" >nul
IF %ERRORLEVEL% EQU 0 (
    echo Deteniendo contenedores existentes...
    docker-compose down
)

echo Iniciando los contenedores...
docker-compose up -d

echo Esperando a que los servicios esten listos...
timeout /t 10 /nobreak >nul

REM Verificar si los contenedores están ejecutándose
docker ps | findstr "backend" >nul
IF %ERRORLEVEL% NEQ 0 (
    echo ERROR: El servicio backend no se inicio correctamente.
    echo Revisando logs:
    docker-compose logs backend
    pause
    exit /b 1
)

docker ps | findstr "frontend" >nul
IF %ERRORLEVEL% NEQ 0 (
    echo ERROR: El servicio frontend no se inicio correctamente.
    echo Revisando logs:
    docker-compose logs frontend
    pause
    exit /b 1
)

docker ps | findstr "mysql" >nul
IF %ERRORLEVEL% NEQ 0 (
    echo ERROR: El servicio mysql no se inicio correctamente.
    echo Revisando logs:
    docker-compose logs mysql
    pause
    exit /b 1
)

REM Inicializar la base de datos
echo Configurando la base de datos...
docker-compose exec backend php setup-db.php

echo.
echo !!! Todo listo !!! La aplicacion esta en ejecucion.
echo.
echo Puedes acceder a:
echo - Interfaz Principal: http://localhost:8000/test-ui.html
echo - Frontend Vue (redirige automaticamente): http://localhost:3000
echo - API de Productos: http://localhost:8000/api/products
echo.
echo Para detener la aplicacion ejecuta: docker-compose down
echo.

ENDLOCAL
pause 