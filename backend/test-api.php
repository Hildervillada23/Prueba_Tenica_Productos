<?php
// Configuración
$baseUrl = 'http://backend/api';  // Usando el nombre del servicio en Docker
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4';

// Función para hacer solicitudes a la API
function callApi($endpoint, $method = 'GET', $data = null) {
    global $baseUrl, $token;
    
    $ch = curl_init("$baseUrl/$endpoint");
    
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'response' => $response ? json_decode($response, true) : null,
        'error' => $error
    ];
}

// Test 1: Obtener todos los productos
echo "Test 1: Obtener todos los productos\n";
$result = callApi('products');
echo "Código de respuesta: " . $result['code'] . "\n";
if ($result['code'] == 200) {
    echo "Productos encontrados: " . count($result['response']) . "\n";
    
    // Guardar el ID del primer producto para pruebas posteriores
    if (!empty($result['response'])) {
        $testProductId = $result['response'][0]['id'];
        echo "ID del producto de prueba: $testProductId\n";
    }
} else {
    echo "Error: " . print_r($result, true) . "\n";
}
echo "\n";

// Test 2: Obtener un producto específico
if (isset($testProductId)) {
    echo "Test 2: Obtener producto con ID $testProductId\n";
    $result = callApi("products/$testProductId");
    echo "Código de respuesta: " . $result['code'] . "\n";
    if ($result['code'] == 200) {
        echo "Producto: " . print_r($result['response'], true) . "\n";
    } else {
        echo "Error: " . print_r($result, true) . "\n";
    }
    echo "\n";
}

// Test 3: Crear un nuevo producto
echo "Test 3: Crear un nuevo producto\n";
$newProduct = [
    'name' => 'Producto de Prueba ' . date('Y-m-d H:i:s'),
    'description' => 'Este es un producto creado por el script de prueba',
    'price' => 99.99
];
$result = callApi('products', 'POST', $newProduct);
echo "Código de respuesta: " . $result['code'] . "\n";
if ($result['code'] == 201) {
    echo "Producto creado: " . print_r($result['response'], true) . "\n";
    $createdProductId = $result['response']['id'];
    echo "ID del producto creado: $createdProductId\n";
} else {
    echo "Error: " . print_r($result, true) . "\n";
}
echo "\n";

// Test 4: Actualizar el producto creado
if (isset($createdProductId)) {
    echo "Test 4: Actualizar producto con ID $createdProductId\n";
    $updatedProduct = [
        'name' => 'Producto Actualizado ' . date('Y-m-d H:i:s'),
        'description' => 'Este producto ha sido actualizado por el script de prueba',
        'price' => 149.99
    ];
    $result = callApi("products/$createdProductId", 'PUT', $updatedProduct);
    echo "Código de respuesta: " . $result['code'] . "\n";
    if ($result['code'] == 200) {
        echo "Producto actualizado: " . print_r($result['response'], true) . "\n";
    } else {
        echo "Error: " . print_r($result, true) . "\n";
    }
    echo "\n";
}

// Test 5: Eliminar el producto creado
if (isset($createdProductId)) {
    echo "Test 5: Eliminar producto con ID $createdProductId\n";
    $result = callApi("products/$createdProductId", 'DELETE');
    echo "Código de respuesta: " . $result['code'] . "\n";
    if ($result['code'] == 200) {
        echo "Respuesta: " . print_r($result['response'], true) . "\n";
        echo "Producto eliminado correctamente\n";
    } else {
        echo "Error: " . print_r($result, true) . "\n";
    }
    echo "\n";
}

echo "Pruebas completadas\n"; 