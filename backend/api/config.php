<?php
// Configuración de la base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
define('DB_USER', getenv('DB_USERNAME') ?: 'sail');
define('DB_PASS', getenv('DB_PASSWORD') ?: 'password');
define('DB_NAME', getenv('DB_DATABASE') ?: 'laravel');

// Configuración CORS
function configureCors() {
    // Obtener origen de la solicitud
    $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*';
    
    // En desarrollo, permitir todos los orígenes
    header("Access-Control-Allow-Origin: *");
    
    // Configuración adicional de CORS
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin");
    header("Access-Control-Max-Age: 86400"); // Cache preflight por 24 horas
    header("Access-Control-Allow-Credentials: true");
    
    // Para solicitudes Content-Type: application/json
    header("Content-Type: application/json; charset=UTF-8");
    
    // Responder a las solicitudes OPTIONS (preflight)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // Devolver 200 OK sin contenido para preflight
        http_response_code(200);
        exit();
    }
}

// Conexión a la base de datos
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die(json_encode([
            'status' => 'error',
            'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error
        ]));
    }
    
    $conn->set_charset("utf8");
    return $conn;
}

// Función para obtener datos del cuerpo de la petición
function getRequestData() {
    return json_decode(file_get_contents('php://input'), true);
}

// Respuesta JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Autenticación simple (usuario quemado)
function authenticate() {
    $headers = getallheaders();
    $token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;
    
    // Token válido quemado para este ejemplo
    $validToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4';
    
    if ($token !== $validToken) {
        jsonResponse(['status' => 'error', 'message' => 'No autorizado'], 401);
    }
    
    return [
        'id' => 1,
        'name' => 'Usuario Demo',
        'email' => 'demo@example.com'
    ];
} 