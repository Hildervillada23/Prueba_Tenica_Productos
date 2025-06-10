#!/bin/bash

echo "=== Configurando el proyecto Mi Catálogo de Productos ==="

# Configurar .env para el backend si no existe
if [ ! -f backend/.env ]; then
  echo "Creando archivo .env para el backend..."
  cp backend/.env.example backend/.env || echo "APP_NAME=\"Mi Catalogo Productos\"
APP_ENV=local
APP_KEY=base64:Sg3FtGUmx6g9hDu6Z0C3SjTMzTACUz6xBnEO+lZ3f6U=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=\"hello@example.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
CORS_ALLOWED_ORIGINS=http://localhost:3000" > backend/.env
fi

# Verificar si Docker está en ejecución
echo "Verificando Docker..."
if ! docker info > /dev/null 2>&1; then
  echo "Docker no está en ejecución. Por favor, inicia Docker y ejecuta este script nuevamente."
  exit 1
fi

# Iniciar los contenedores
echo "Iniciando contenedores con Docker Compose..."
docker-compose down
docker-compose up -d

# Esperar a que los contenedores estén listos
echo "Esperando a que los contenedores estén listos..."
sleep 10

# Instalar dependencias de Composer
echo "Instalando dependencias de Composer en el backend..."
docker-compose exec backend composer install

# Generar clave de aplicación
echo "Generando clave de aplicación Laravel..."
docker-compose exec backend php artisan key:generate --ansi

# Ejecutar migraciones y seeders
echo "Ejecutando migraciones y sembrando la base de datos..."
docker-compose exec backend php artisan migrate:fresh --seed

# Instalar dependencias de npm para el frontend
echo "Instalando dependencias de npm en el frontend..."
docker-compose exec frontend npm install

# Compilar frontend
echo "Compilando el frontend..."
docker-compose exec frontend npm run build

echo "=== Configuración completada ==="
echo "El backend está disponible en: http://localhost:8000"
echo "El frontend está disponible en: http://localhost:3000"
echo "Credenciales de usuario demo:"
echo "  Email: demo@example.com"
echo "  Contraseña: password123" 