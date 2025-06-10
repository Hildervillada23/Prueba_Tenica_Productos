<?php
require_once 'config.php';

configureCors();

// Verificar autenticación para todas las solicitudes excepto OPTIONS
if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
    $user = authenticate();
}

// Conexión a la base de datos
$conn = getDbConnection();

// Endpoint para obtener todos los productos (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['id'])) {
    $query = "SELECT * FROM products ORDER BY id DESC";
    $result = $conn->query($query);
    
    if ($result) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        jsonResponse($products);
    } else {
        jsonResponse(['status' => 'error', 'message' => 'Error al obtener productos: ' . $conn->error], 500);
    }
}

// Endpoint para obtener un producto específico (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        jsonResponse($product);
    } else {
        jsonResponse(['status' => 'error', 'message' => 'Producto no encontrado'], 404);
    }
}

// Endpoint para crear un producto (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = getRequestData();
    
    // Validar datos requeridos
    if (!isset($data['name']) || !isset($data['price'])) {
        jsonResponse(['status' => 'error', 'message' => 'Nombre y precio son obligatorios'], 400);
    }
    
    $name = $conn->real_escape_string($data['name']);
    $description = $conn->real_escape_string($data['description'] ?? '');
    $price = floatval($data['price']);
    
    $query = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', $price)";
    
    if ($conn->query($query)) {
        $id = $conn->insert_id;
        jsonResponse([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'price' => $price,
            'status' => 'success',
            'message' => 'Producto creado exitosamente'
        ], 201);
    } else {
        jsonResponse(['status' => 'error', 'message' => 'Error al crear producto: ' . $conn->error], 500);
    }
}

// Endpoint para actualizar un producto (PUT)
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $data = getRequestData();
    
    // Validar que el producto exista
    $checkQuery = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($checkQuery);
    
    if (!$result || $result->num_rows === 0) {
        jsonResponse(['status' => 'error', 'message' => 'Producto no encontrado'], 404);
    }
    
    // Construir la consulta de actualización
    $updateFields = [];
    
    if (isset($data['name'])) {
        $name = $conn->real_escape_string($data['name']);
        $updateFields[] = "name = '$name'";
    }
    
    if (isset($data['description'])) {
        $description = $conn->real_escape_string($data['description']);
        $updateFields[] = "description = '$description'";
    }
    
    if (isset($data['price'])) {
        $price = floatval($data['price']);
        $updateFields[] = "price = $price";
    }
    
    if (empty($updateFields)) {
        jsonResponse(['status' => 'error', 'message' => 'No se proporcionaron campos para actualizar'], 400);
    }
    
    $query = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE id = $id";
    
    if ($conn->query($query)) {
        // Obtener producto actualizado
        $getQuery = "SELECT * FROM products WHERE id = $id";
        $result = $conn->query($getQuery);
        $updatedProduct = $result->fetch_assoc();
        
        jsonResponse($updatedProduct);
    } else {
        jsonResponse(['status' => 'error', 'message' => 'Error al actualizar producto: ' . $conn->error], 500);
    }
}

// Endpoint para eliminar un producto (DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Validar que el producto exista
    $checkQuery = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($checkQuery);
    
    if (!$result || $result->num_rows === 0) {
        jsonResponse(['status' => 'error', 'message' => 'Producto no encontrado'], 404);
    }
    
    $query = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($query)) {
        // Respuesta exitosa para DELETE con el ID eliminado
        jsonResponse(['status' => 'success', 'message' => 'Producto eliminado exitosamente', 'id' => $id]);
    } else {
        jsonResponse(['status' => 'error', 'message' => 'Error al eliminar producto: ' . $conn->error], 500);
    }
}

// Si llegamos aquí, el método solicitado no está implementado
jsonResponse(['status' => 'error', 'message' => 'Método no implementado'], 501); 