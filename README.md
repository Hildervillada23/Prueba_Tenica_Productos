# Mi Catálogo de Productos

Un catálogo de productos CRUD completo con backend PHP, interfaz HTML/CSS/JS moderna y dockerización.

## Resumen del Proyecto

Este proyecto es una aplicación web que permite gestionar un catálogo de productos, implementando todas las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) a través de una API RESTful en PHP y una interfaz de usuario interactiva.

### Características principales:

- **Frontend moderno** con HTML, CSS y JavaScript nativo
- **Backend RESTful** en PHP nativo
- **Persistencia** en MySQL
- **Completamente dockerizado** para fácil ejecución en cualquier entorno
- **Arquitectura MVC** con separación de responsabilidades
- **Seguridad** implementada con validación de datos y autenticación por token

## Herramientas Necesarias

Para ejecutar este proyecto solo necesitas:

1. **Docker Desktop** - Para gestionar los contenedores
2. **Git** - Para control de versiones

Si no usas Docker, consulta el archivo `HERRAMIENTAS-DESARROLLO.md` para ver la lista completa de herramientas necesarias.

## Instalación Rápida

### En Windows:

```bash
# Clonar el repositorio
git clone <url-del-repositorio>
cd mi-catalogo-productos

# Ejecutar el script de inicio
run-project.bat
```

### En Linux/Mac:

```bash
# Clonar el repositorio
git clone <url-del-repositorio>
cd mi-catalogo-productos

# Dar permisos de ejecución al script
chmod +x run-project.sh

# Ejecutar el script de inicio
./run-project.sh
```

## Acceso a la Aplicación

- **Interfaz Principal**: http://localhost:8000/test-ui.html
- **API de Productos**: http://localhost:8000/api/products

## Acceso a la Base de Datos

Para gestionar directamente la base de datos MySQL, puedes utilizar phpMyAdmin a través de la siguiente URL:

- **phpMyAdmin**: http://localhost:8080

Credenciales de acceso:
- **Servidor**: mysql
- **Usuario**: sail
- **Contraseña**: password
- **Base de datos**: laravel

Si deseas cambiar la configuración del puerto de phpMyAdmin, edita el archivo `docker-compose.yml` añadiendo el siguiente servicio:

```yaml
phpmyadmin:
  image: phpmyadmin/phpmyadmin
  ports:
    - "8080:80"
  environment:
    - PMA_HOST=mysql
    - PMA_PORT=3306
  depends_on:
    - mysql
  networks:
    - app-network
```

## Características Avanzadas

Además de las funcionalidades básicas CRUD, este proyecto incluye características avanzadas:

### 1. Documentación API con Swagger/OpenAPI
- **URL**: http://localhost:8000/api-docs.html
- Descripción detallada de todos los endpoints de la API
- Ejemplos de solicitudes y respuestas
- Esquemas de datos

### 2. Pruebas Unitarias
- Script de pruebas para la API en `backend/tests/api_test.php`
- Para ejecutar las pruebas:
  ```bash
  docker-compose exec backend php tests/api_test.php
  ```

### 3. Sistema de Logging
- Logs detallados de todas las operaciones de la API
- Ubicación de logs: `backend/logs/api_YYYY-MM-DD.log`
- Registra información como IP, método HTTP, URI, agente de usuario y más

### 4. Soporte HTTPS
- Acceso seguro a través de https://localhost:8443
- Certificado SSL autofirmado generado automáticamente
- Configuración de seguridad recomendada para SSL/TLS

## Estructura del Proyecto

- **backend/**: Backend PHP nativo
  - `api/products.php`: Endpoints CRUD para productos
  - `test-ui.html`: Interfaz HTML/CSS/JS para gestionar productos
  - `setup-db.php`: Script para inicializar la base de datos
  - `Dockerfile`: Configuración para crear el contenedor backend

- **docker-compose.yml**: Configuración Docker para todos los servicios

## Funcionalidades Implementadas

La aplicación permite:

1. **Listar todos los productos** con su nombre, descripción y precio
2. **Crear nuevos productos** con validación de datos
3. **Ver detalles** de productos individuales en modal interactivo
4. **Editar productos** existentes a través de formulario modal
5. **Eliminar productos** con confirmación visual

## Tecnologías Utilizadas

- **Backend**:
  - PHP 8.2 nativo
  - MySQL 8.0
  - Apache

- **Frontend**:
  - HTML5, CSS3 y JavaScript moderno
  - Fetch API para comunicación asíncrona
  - Diseño responsivo y adaptable a dispositivos móviles
  - Interfaces modales para mejorar UX

- **Infraestructura**:
  - Docker y Docker Compose
  - Contenedores separados para backend y base de datos

## Detalles Técnicos

### API RESTful

La API implementa los siguientes endpoints:

- `GET /api/products`: Obtener todos los productos
- `GET /api/products/{id}`: Obtener un producto específico
- `POST /api/products`: Crear un nuevo producto
- `PUT /api/products/{id}`: Actualizar un producto existente
- `DELETE /api/products/{id}`: Eliminar un producto

### Modelo de Datos

La base de datos MySQL contiene una tabla `products` con los siguientes campos:

- `id`: Identificador único (clave primaria)
- `name`: Nombre del producto (string)
- `description`: Descripción del producto (text)
- `price`: Precio del producto (decimal)
- `created_at`: Fecha de creación (timestamp)
- `updated_at`: Fecha de última actualización (timestamp)

### Seguridad

- Validación de entradas tanto en el frontend como en el backend
- Escape de caracteres para prevenir inyección SQL
- Autenticación mediante token Bearer
- Configuración CORS para permitir solicitudes desde el frontend
- Mensajes de error amigables que no exponen detalles técnicos

## Solución de problemas comunes

### Si los contenedores no inician

1. Verifica que Docker esté instalado y en ejecución
2. Asegúrate de que los puertos 8000 y 3306 estén disponibles
3. Elimina contenedores e imágenes anteriores:

```bash
docker-compose down
docker system prune -a
```

4. Intenta iniciar los contenedores nuevamente:

```bash
docker-compose up -d
```

### Si no puedes acceder a la aplicación

1. Verifica que los contenedores estén en ejecución con:

```bash
docker-compose ps
```

2. Comprueba los logs de los contenedores:

```bash
docker-compose logs
```

## Cumplimiento de Requisitos de la Prueba

Este proyecto cumple con todos los requisitos especificados en la prueba técnica:

1. ✅ **Frontend simple**: Interfaz completamente funcional para gestión de productos
2. ✅ **Backend en PHP**: API RESTful implementada con PHP nativo
3. ✅ **Persistencia**: Datos almacenados en MySQL
4. ✅ **Dockerización**: Entorno completo configurado con Docker Compose
5. ✅ **Buenas prácticas**: Código organizado, validación de datos, gestión de errores
6. ✅ **Seguridad**: Validación de entradas, autenticación por token, prevención de inyección SQL 