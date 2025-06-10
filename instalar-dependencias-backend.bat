@echo off
echo === Instalando dependencias del backend ===

REM Verificar si Docker está en ejecución
docker info >nul 2>&1
if errorlevel 1 (
    echo Docker no esta en ejecucion. Por favor, inicia Docker y ejecuta este script nuevamente.
    exit /b 1
)

REM Verificar si los contenedores están en ejecución
docker-compose ps backend | findstr "Up" >nul
if errorlevel 1 (
    echo Iniciando contenedores...
    docker-compose up -d
    timeout /t 10 /nobreak >nul
)

REM Instalar dependencias de Composer
echo Instalando dependencias de Composer...
docker-compose exec -T backend composer install

REM Generar archivos de autoload
echo Actualizando autoload...
docker-compose exec -T backend composer dump-autoload

REM Limpiar caché
echo Limpiando cache...
docker-compose exec -T backend php artisan cache:clear
docker-compose exec -T backend php artisan config:clear
docker-compose exec -T backend php artisan route:clear

REM Ejecutar migraciones si es necesario
echo Verificando base de datos...
docker-compose exec -T backend php artisan migrate --force

echo === Instalacion de dependencias completada ===
echo Para iniciar la aplicacion completa, ejecuta:
echo setup-project.bat 