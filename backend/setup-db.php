<?php
// Configuración de la base de datos
$host = getenv('DB_HOST') ?: 'mysql';
$user = getenv('DB_USERNAME') ?: 'sail';
$pass = getenv('DB_PASSWORD') ?: 'password';
$dbname = getenv('DB_DATABASE') ?: 'laravel';

// Conectar a la base de datos
echo "Conectando a la base de datos...\n";
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error . "\n");
}
echo "Conexión exitosa!\n";

// Crear tabla de productos
echo "Creando tabla de productos...\n";
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'products' creada correctamente\n";
} else {
    echo "Error al crear la tabla: " . $conn->error . "\n";
}

// Verificar si hay productos
$result = $conn->query("SELECT COUNT(*) as total FROM products");
$row = $result->fetch_assoc();

if ($row['total'] == 0) {
    // Insertar datos de ejemplo
    echo "Insertando datos de ejemplo...\n";
    
    $products = [
        ['Smartphone Galaxy S23', 'Último modelo de Samsung con cámara de 108MP y pantalla AMOLED de 6.8 pulgadas.', 899.99],
        ['Laptop ThinkPad X1', 'Portátil profesional con procesador Intel i7, 16GB RAM y 1TB SSD.', 1299.99],
        ['Monitor UltraWide 34"', 'Monitor curvo con resolución 3440x1440, HDR y 144Hz.', 449.99],
        ['Teclado Mecánico RGB', 'Teclado gaming con switches Cherry MX Blue y retroiluminación personalizable.', 89.99],
        ['Cámara DSLR Canon', 'Cámara profesional con sensor full-frame de 30MP y grabación 4K.', 1599.99],
        ['Auriculares Noise Cancelling', 'Auriculares premium con cancelación de ruido activa y 30 horas de batería.', 249.99]
    ];
    
    foreach ($products as $product) {
        $name = $conn->real_escape_string($product[0]);
        $description = $conn->real_escape_string($product[1]);
        $price = $product[2];
        
        $sql = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', $price)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Producto '$name' insertado correctamente\n";
        } else {
            echo "Error al insertar producto: " . $conn->error . "\n";
        }
    }
} else {
    echo "Ya existen " . $row['total'] . " productos en la base de datos\n";
}

// Cerrar la conexión
$conn->close();
echo "Inicialización de la base de datos completada!\n"; 