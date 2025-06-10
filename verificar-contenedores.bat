@echo off
echo === Verificando el estado de los contenedores ===

REM Obtener el estado de los contenedores
echo Contenedores en ejecucion:
docker ps

echo.
echo === Verificando acceso a los contenedores ===

REM Probar acceso al backend
echo Probando acceso al backend:
docker-compose exec -T backend php -v
if errorlevel 1 (
    echo [ERROR] No se puede acceder al contenedor del backend
) else (
    echo [OK] Contenedor backend responde correctamente
)

REM Probar acceso al frontend
echo Probando acceso al frontend:
docker-compose exec -T frontend node -v
if errorlevel 1 (
    echo [ERROR] No se puede acceder al contenedor del frontend
) else (
    echo [OK] Contenedor frontend responde correctamente
)

REM Probar acceso a la base de datos
echo Probando acceso a la base de datos:
docker-compose exec -T mysql mysql --version
if errorlevel 1 (
    echo [ERROR] No se puede acceder al contenedor de MySQL
) else (
    echo [OK] Contenedor MySQL responde correctamente
)

echo.
echo === Para iniciar todo el proyecto, ejecuta: ===
echo setup-project.bat 