<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Responder a las solicitudes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Respuesta de prueba
echo json_encode([
    'message' => 'Bienvenido a la API de Mi Catálogo de Productos',
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s')
]); 