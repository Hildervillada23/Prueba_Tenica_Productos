# Herramientas de Desarrollo

Este documento describe las herramientas necesarias para desarrollar y ejecutar el proyecto Mi Catálogo de Productos sin utilizar Docker. Si usas Docker, no necesitarás instalar estas herramientas individualmente en tu máquina.

## Herramientas Básicas

### 1. PHP (versión 8.0 o superior)
- **Descripción**: Lenguaje de programación utilizado en el backend.
- **Instalación**:
  - Windows: [PHP for Windows](https://windows.php.net/download/)
  - macOS: `brew install php`
  - Linux (Ubuntu): `sudo apt install php8.0-cli php8.0-mysql`
- **Extensiones necesarias**:
  - php-mysql
  - php-json
  - php-mbstring

### 2. MySQL (versión 8.0)
- **Descripción**: Sistema de gestión de bases de datos para almacenar los productos.
- **Instalación**:
  - Windows: [MySQL Installer](https://dev.mysql.com/downloads/installer/)
  - macOS: `brew install mysql`
  - Linux (Ubuntu): `sudo apt install mysql-server`
- **Configuración recomendada**:
  - Usuario: `sail`
  - Contraseña: `password`
  - Base de datos: `laravel`

### 3. Servidor Web (Apache/Nginx)
- **Descripción**: Servidor para ejecutar la aplicación PHP.
- **Instalación**:
  - Windows: [XAMPP](https://www.apachefriends.org/index.html) incluye Apache, PHP y MySQL
  - macOS: `brew install httpd`
  - Linux (Ubuntu): `sudo apt install apache2`

### 4. Git
- **Descripción**: Sistema de control de versiones.
- **Instalación**:
  - Windows: [Git for Windows](https://gitforwindows.org/)
  - macOS: `brew install git`
  - Linux (Ubuntu): `sudo apt install git`

## Herramientas Opcionales para Desarrollo

### 1. Composer
- **Descripción**: Gestor de dependencias para PHP.
- **Instalación**: [Composer Download](https://getcomposer.org/download/)
- **Uso**: No es estrictamente necesario para este proyecto, pero puede ser útil para instalar bibliotecas adicionales.

### 2. Postman
- **Descripción**: Herramienta para probar APIs.
- **Instalación**: [Postman Download](https://www.postman.com/downloads/)
- **Uso**: Facilita las pruebas de los endpoints de la API de productos.

### 3. IDE/Editor de Código
- **Recomendaciones**:
  - [Visual Studio Code](https://code.visualstudio.com/) con extensiones para PHP
  - [PhpStorm](https://www.jetbrains.com/phpstorm/) (IDE específico para PHP)
  - [Sublime Text](https://www.sublimetext.com/)

## Configuración del Entorno sin Docker

Si decides no utilizar Docker, sigue estos pasos para configurar el entorno manualmente:

### 1. Configuración de la Base de Datos

```sql
CREATE DATABASE laravel;
CREATE USER 'sail'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON laravel.* TO 'sail'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configuración del Servidor Web (Apache)

Asegúrate de que el módulo `mod_rewrite` esté habilitado:

```bash
# En Ubuntu
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Configura un VirtualHost (ejemplo para Apache):

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /ruta/a/mi-catalogo-productos/backend
    
    <Directory /ruta/a/mi-catalogo-productos/backend>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### 3. Inicialización de la Base de Datos

Ejecuta el script de inicialización:

```bash
php backend/setup-db.php
```

## Verificación de la Instalación

Para verificar que todo está configurado correctamente:

1. Abre el navegador y accede a `http://localhost/test-ui.html`
2. Deberías ver la interfaz del catálogo de productos
3. Intenta crear un nuevo producto para verificar que la conexión con la base de datos funciona

## Solución de Problemas Comunes

### Problemas de conexión a la base de datos
- Verifica las credenciales en `backend/api/db_config.php`
- Asegúrate de que el servicio MySQL esté en ejecución

### Problemas con las rutas de la API
- Revisa la configuración del archivo `.htaccess`
- Verifica que el módulo `mod_rewrite` esté habilitado en Apache

### Errores de permisos
- Asegúrate de que el usuario del servidor web tenga permisos de lectura/escritura en el directorio del proyecto 