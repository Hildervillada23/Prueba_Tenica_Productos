// Configuración global de la aplicación

// URL base de la API - Usar la URL completa cuando se ejecuta en localhost:3000
export const API_URL = 'http://localhost:8000/api'

// Credenciales predefinidas para autenticación (usuario quemado)
export const DEFAULT_CREDENTIALS = {
  email: 'demo@example.com',
  password: 'password123'
}

// Tiempo de expiración del token (en milisegundos)
export const TOKEN_EXPIRATION = 24 * 60 * 60 * 1000 // 24 horas

// Token de autenticación (token quemado para pruebas)
export const API_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4' 