FROM node:18-alpine AS build

WORKDIR /app

# Copiar package.json y package-lock.json
COPY package*.json ./

# Instalar dependencias
RUN npm install

# Copiar el código fuente
COPY . .

# Compilar la aplicación
RUN npm run build

# Imagen de producción con Nginx
FROM nginx:alpine

# Copiar la configuración personalizada de Nginx
COPY nginx-custom.conf /etc/nginx/conf.d/default.conf

# Copiar los archivos compilados desde la etapa de construcción
COPY --from=build /app/dist /usr/share/nginx/html

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Nginx
CMD ["nginx", "-g", "daemon off;"] 