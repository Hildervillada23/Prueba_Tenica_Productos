<?php
require_once 'config.php';

configureCors();

// Solo aceptamos POST para login
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['status' => 'error', 'message' => 'Método no permitido'], 405);
}

// Obtener datos de la solicitud
$data = getRequestData();

// Verificar credenciales (usuario quemado)
$validEmail = 'demo@example.com';
$validPassword = 'password123';

if (!isset($data['email']) || !isset($data['password']) || 
    $data['email'] !== $validEmail || $data['password'] !== $validPassword) {
    jsonResponse(['status' => 'error', 'message' => 'Credenciales inválidas'], 401);
}

// Generar token (en un sistema real, usaríamos JWT con una librería adecuada)
// Este token ya está configurado en config.php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4';

// Responder con token y datos del usuario
jsonResponse([
    'status' => 'success',
    'message' => 'Inicio de sesión exitoso',
    'token' => $token,
    'user' => [
        'id' => 1,
        'name' => 'Usuario Demo',
        'email' => $validEmail
    ]
]); 