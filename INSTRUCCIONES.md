# INSTRUCCIONES PARA EJECUTAR MI CATÁLOGO DE PRODUCTOS

## Requisitos previos

- Docker Desktop instalado y en ejecución
- Docker Compose instalado

## Pasos para ejecutar la aplicación

### 1. Verificar que Docker esté en ejecución

Antes de iniciar, asegúrate de que Docker Desktop esté ejecutándose en tu sistema.

### 2. Verificar el estado de los contenedores

Ejecuta el siguiente comando para verificar si los contenedores pueden ser accedidos correctamente:

```
verificar-contenedores.bat
```

### 3. Instalar dependencias del backend

Si el contenedor del backend tiene problemas con las dependencias, ejecuta:

```
instalar-dependencias-backend.bat
```

### 4. Configurar y ejecutar la aplicación completa

Para configurar y ejecutar la aplicación completa, usa:

```
setup-project.bat
```

Este script realizará las siguientes acciones:
- Creará los archivos de configuración necesarios
- Iniciará los contenedores Docker
- Instalará las dependencias
- Ejecutará las migraciones y seeders
- Compilará el frontend

### 5. Acceder a la aplicación

Una vez que el script haya finalizado, podrás acceder a:

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api

### Credenciales del usuario demo

Para acceder a la API, utiliza estas credenciales:

- **Email**: demo@example.com
- **Password**: password123

## Solución a problemas de ejecución

Hemos detectado algunos problemas en la configuración del proyecto. Sigue estos pasos para solucionarlos:

### 1. Crear archivo .env en el backend

Primero, crea un archivo `.env` en la carpeta `backend/` con el siguiente contenido:

```
APP_NAME="Mi Catalogo Productos"
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

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
CORS_ALLOWED_ORIGINS=http://localhost:3000
```

### 2. Iniciar los contenedores manualmente

Ejecuta estos comandos en orden:

```
# Detener contenedores previos
docker-compose down

# Iniciar los contenedores
docker-compose up -d

# Esperar a que los contenedores estén listos (10-15 segundos)

# Instalar dependencias en el backend
docker-compose exec backend composer install

# Generar clave de aplicación
docker-compose exec backend php artisan key:generate --ansi

# Ejecutar migraciones y seeders
docker-compose exec backend php artisan migrate:fresh --seed

# Instalar dependencias en el frontend
docker-compose exec frontend npm install

# Compilar el frontend
docker-compose exec frontend npm run build
```

### 3. Acceder a la aplicación

Una vez que el proceso haya finalizado correctamente, podrás acceder a:

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api

### Credenciales del usuario demo

Para acceder a la API, utiliza estas credenciales:

- **Email**: demo@example.com
- **Password**: password123

## Acerca de los errores de linter

Los errores de linter que aparecen en los archivos PHP (como User.php, DatabaseSeeder.php, etc.) no afectan el funcionamiento de la aplicación, ya que estos archivos se ejecutan dentro de un contenedor Docker con todas las dependencias de Laravel correctamente instaladas.

## Solución de problemas comunes

### Si los contenedores no inician correctamente

1. Verifica que Docker Desktop esté en ejecución
2. Asegúrate de que los puertos 3000, 8000 y 3306 estén disponibles
3. Elimina todas las imágenes y contenedores anteriores:

```
docker-compose down
docker system prune -a
```

4. Intenta iniciar los contenedores nuevamente:

```
docker-compose up -d
```

### Si hay errores al ejecutar las migraciones

1. Verifica que el contenedor de MySQL esté en ejecución:

```
docker-compose ps
```

2. Reinicia el contenedor de MySQL:

```
docker-compose restart mysql
```

3. Espera unos segundos y vuelve a intentar las migraciones:

```
docker-compose exec backend php artisan migrate:fresh --seed
```

## Estructura del proyecto

- **frontend/**: Aplicación Vue.js para el frontend
- **backend/**: API Laravel para el backend
- **docker-compose.yml**: Configuración de los contenedores

## Funcionalidades implementadas

- Listado de productos
- Detalle de producto
- Creación de productos
- Edición de productos
- Eliminación de productos
- Autenticación de API con usuario predefinido (quemado)

# Instrucciones de la Prueba Técnica

## Resumen

Esta prueba técnica consiste en desarrollar una aplicación web CRUD para gestionar un catálogo de productos, con un tiempo aproximado de 2 horas para su implementación.

## Objetivo

Crear una aplicación web que permita realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre productos, integrando:

- Frontend para visualización y gestión
- Backend RESTful en PHP
- Persistencia en base de datos MySQL
- Dockerización completa
- Buenas prácticas de desarrollo
- Seguridad básica

## Requisitos Funcionales

### 1. Frontend

- ✅ Mostrar una lista de productos con nombre, descripción y precio
- ✅ Permitir agregar nuevos productos
- ✅ Permitir editar productos existentes
- ✅ Permitir eliminar productos
- ✅ Interfaz intuitiva y responsiva

**Herramientas recomendadas:**
- Vue.js 2/3 (si hay experiencia previa)
- Frameworks CSS (Vuetify, Quasar, TailwindCSS)
- Alternativa: HTML, CSS y JavaScript nativo

### 2. Backend (PHP)

- ✅ API RESTful con endpoints para:
  - GET /products - Listar todos los productos
  - GET /products/{id} - Obtener un producto específico
  - POST /products - Crear un nuevo producto
  - PUT /products/{id} - Actualizar un producto
  - DELETE /products/{id} - Eliminar un producto
- ✅ Validación de datos
- ✅ Manejo de errores

**Herramientas recomendadas:**
- Laravel Sail/Lumen (si hay experiencia previa)
- Alternativa: PHP nativo

### 3. Persistencia

- ✅ Base de datos MySQL con tabla de productos
- ✅ Campos mínimos: id, nombre, descripción, precio
- ✅ Uso de ORM o consultas SQL directas

### 4. Dockerización

- ✅ Archivo docker-compose.yml para:
  - Contenedor backend (PHP)
  - Contenedor base de datos (MySQL)
  - Contenedor frontend (opcional)
- ✅ La aplicación debe poder ejecutarse con docker-compose up

### 5. Buenas Prácticas

- ✅ Control de versiones con Git
- ✅ Validación de datos en frontend y backend
- ✅ Uso de .env para configuraciones
- ✅ Manejo de errores adecuado

### 6. Seguridad

- ✅ Validación de entradas para prevenir inyección SQL
- ✅ Autenticación básica (token simple)
- ✅ Configuración CORS adecuada

## Estado Actual del Proyecto

El proyecto ya ha implementado:

1. ✅ **Frontend funcional** con HTML, CSS y JavaScript moderno:
   - Interfaz de usuario completamente responsiva
   - Modales para crear, editar y ver detalles
   - Confirmación visual para eliminación
   - Validación de formularios

2. ✅ **Backend PHP**:
   - API RESTful completa
   - Validación y manejo de errores
   - Comunicación con base de datos MySQL

3. ✅ **Dockerización**:
   - Configuración completa con docker-compose.yml
   - Entorno listo para ejecutarse en cualquier máquina

4. ✅ **Seguridad**:
   - Validación de datos
   - Autenticación por token
   - Prevención de inyección SQL

## Próximos Pasos (Posibles Mejoras)

Si quisieras continuar mejorando el proyecto, podrías considerar:

1. **Pruebas automatizadas**:
   - Implementar tests unitarios para el backend
   - Añadir tests e2e para el frontend

2. **Funcionalidades adicionales**:
   - Paginación para la lista de productos
   - Filtrado y búsqueda
   - Categorización de productos
   - Imágenes para los productos

3. **Mejoras de UX/UI**:
   - Animaciones más elaboradas
   - Tema oscuro/claro
   - Más opciones de personalización

4. **Seguridad avanzada**:
   - Sistema de autenticación completo (registro, login)
   - Roles y permisos
   - Protección contra CSRF y XSS

## Conclusión

El proyecto cumple con todos los requisitos especificados en la prueba técnica, implementando una aplicación web CRUD completa con backend PHP, frontend moderno y completamente dockerizada. 