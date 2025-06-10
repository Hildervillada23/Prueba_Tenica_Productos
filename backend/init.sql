-- Crear tabla de productos si no existe
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar datos de ejemplo si la tabla está vacía
INSERT INTO products (name, description, price)
SELECT * FROM (
    SELECT 'Smartphone Galaxy S23', 'Último modelo de Samsung con cámara de 108MP y pantalla AMOLED de 6.8 pulgadas.', 899.99 UNION ALL
    SELECT 'Laptop ThinkPad X1', 'Portátil profesional con procesador Intel i7, 16GB RAM y 1TB SSD.', 1299.99 UNION ALL
    SELECT 'Monitor UltraWide 34"', 'Monitor curvo con resolución 3440x1440, HDR y 144Hz.', 449.99 UNION ALL
    SELECT 'Teclado Mecánico RGB', 'Teclado gaming con switches Cherry MX Blue y retroiluminación personalizable.', 89.99 UNION ALL
    SELECT 'Cámara DSLR Canon', 'Cámara profesional con sensor full-frame de 30MP y grabación 4K.', 1599.99 UNION ALL
    SELECT 'Auriculares Noise Cancelling', 'Auriculares premium con cancelación de ruido activa y 30 horas de batería.', 249.99
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM products LIMIT 1); 