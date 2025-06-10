#!/bin/bash

# Colores para los mensajes
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}=== Mi Catálogo de Productos - Script de Inicio ===${NC}"
echo -e "${YELLOW}Este script verificará e iniciará todos los componentes necesarios.${NC}"
echo

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${RED}Docker no está instalado. Por favor, instálalo primero.${NC}"
    echo "Puedes descargarlo desde: https://www.docker.com/products/docker-desktop"
    exit 1
fi

# Verificar si docker-compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}Docker Compose no está instalado. Por favor, instálalo primero.${NC}"
    echo "Normalmente viene incluido con Docker Desktop."
    exit 1
fi

echo -e "${YELLOW}Verificando si hay contenedores en ejecución...${NC}"
if docker ps | grep -q "mi-catalogo-productos"; then
    echo -e "${YELLOW}Deteniendo contenedores existentes...${NC}"
    docker-compose down
fi

echo -e "${GREEN}Iniciando los contenedores...${NC}"
docker-compose up -d

echo -e "${YELLOW}Esperando a que los servicios estén listos...${NC}"
sleep 10

# Verificar si los contenedores están ejecutándose
if ! docker ps | grep -q "backend"; then
    echo -e "${RED}El servicio backend no se inició correctamente.${NC}"
    echo "Revisando logs:"
    docker-compose logs backend
    exit 1
fi

if ! docker ps | grep -q "frontend"; then
    echo -e "${RED}El servicio frontend no se inició correctamente.${NC}"
    echo "Revisando logs:"
    docker-compose logs frontend
    exit 1
fi

if ! docker ps | grep -q "mysql"; then
    echo -e "${RED}El servicio mysql no se inició correctamente.${NC}"
    echo "Revisando logs:"
    docker-compose logs mysql
    exit 1
fi

# Inicializar la base de datos
echo -e "${GREEN}Configurando la base de datos...${NC}"
docker-compose exec backend php setup-db.php

echo -e "${GREEN}¡Todo listo! La aplicación está en ejecución.${NC}"
echo 
echo -e "${YELLOW}Puedes acceder a:${NC}"
echo -e "- Interfaz Principal: ${GREEN}http://localhost:8000/test-ui.html${NC}"
echo -e "- Frontend Vue (redirige automáticamente): ${GREEN}http://localhost:3000${NC}"
echo -e "- API de Productos: ${GREEN}http://localhost:8000/api/products${NC}"
echo
echo -e "${YELLOW}Para detener la aplicación ejecuta:${NC} docker-compose down"
echo 