@echo off
echo === Configurando el proyecto Mi Catalogo de Productos ===

REM Configurar .env para el backend si no existe
if not exist backend\.env (
    echo Creando archivo .env para el backend...
    copy backend\.env.example backend\.env 2>nul
    if errorlevel 1 (
        echo APP_NAME="Mi Catalogo Productos" > backend\.env
        echo APP_ENV=local >> backend\.env
        echo APP_KEY=base64:Sg3FtGUmx6g9hDu6Z0C3SjTMzTACUz6xBnEO+lZ3f6U= >> backend\.env
        echo APP_DEBUG=true >> backend\.env
        echo APP_URL=http://localhost:8000 >> backend\.env
        echo. >> backend\.env
        echo LOG_CHANNEL=stack >> backend\.env
        echo LOG_DEPRECATIONS_CHANNEL=null >> backend\.env
        echo LOG_LEVEL=debug >> backend\.env
        echo. >> backend\.env
        echo DB_CONNECTION=mysql >> backend\.env
        echo DB_HOST=mysql >> backend\.env
        echo DB_PORT=3306 >> backend\.env
        echo DB_DATABASE=laravel >> backend\.env
        echo DB_USERNAME=sail >> backend\.env
        echo DB_PASSWORD=password >> backend\.env
        echo. >> backend\.env
        echo BROADCAST_DRIVER=log >> backend\.env
        echo CACHE_DRIVER=file >> backend\.env
        echo FILESYSTEM_DISK=local >> backend\.env
        echo QUEUE_CONNECTION=sync >> backend\.env
        echo SESSION_DRIVER=file >> backend\.env
        echo SESSION_LIFETIME=120 >> backend\.env
        echo. >> backend\.env
        echo MEMCACHED_HOST=127.0.0.1 >> backend\.env
        echo. >> backend\.env
        echo REDIS_HOST=redis >> backend\.env
        echo REDIS_PASSWORD=null >> backend\.env
        echo REDIS_PORT=6379 >> backend\.env
        echo. >> backend\.env
        echo SANCTUM_STATEFUL_DOMAINS=localhost:3000 >> backend\.env
        echo SESSION_DOMAIN=localhost >> backend\.env
        echo CORS_ALLOWED_ORIGINS=http://localhost:3000 >> backend\.env
    )
)

REM Verificar si Docker está en ejecución
echo Verificando Docker...
docker info >nul 2>&1
if errorlevel 1 (
    echo Docker no esta en ejecucion. Por favor, inicia Docker y ejecuta este script nuevamente.
    exit /b 1
)

REM Iniciar los contenedores
echo Iniciando contenedores con Docker Compose...
docker-compose down
docker-compose up -d

REM Esperar a que los contenedores estén listos
echo Esperando a que los contenedores esten listos...
timeout /t 10 /nobreak >nul

REM Instalar dependencias de Composer
echo Instalando dependencias de Composer en el backend...
docker-compose exec -T backend composer install

REM Generar clave de aplicación
echo Generando clave de aplicacion Laravel...
docker-compose exec -T backend php artisan key:generate --ansi

REM Ejecutar migraciones y seeders
echo Ejecutando migraciones y sembrando la base de datos...
docker-compose exec -T backend php artisan migrate:fresh --seed

REM Instalar dependencias de npm para el frontend
echo Instalando dependencias de npm en el frontend...
docker-compose exec -T frontend npm install

REM Compilar frontend
echo Compilando el frontend...
docker-compose exec -T frontend npm run build

echo === Configuracion completada ===
echo El backend esta disponible en: http://localhost:8000
echo El frontend esta disponible en: http://localhost:3000
echo Credenciales de usuario demo:
echo   Email: demo@example.com
echo   Contrasena: password123 