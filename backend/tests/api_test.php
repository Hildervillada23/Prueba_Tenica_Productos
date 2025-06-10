<?php
/**
 * Tests unitarios para la API de productos
 * Para ejecutar: php api_test.php
 */

// Configuración para tests
define('API_URL', 'http://localhost:8000/api');
define('API_TOKEN', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4');

class ProductAPITest {
    private $testProduct = [
        'name' => 'Producto de Prueba',
        'description' => 'Este es un producto para pruebas unitarias',
        'price' => 999.99
    ];
    
    private $createdProductId = null;
    
    /**
     * Ejecuta todas las pruebas
     */
    public function runTests() {
        echo "Iniciando pruebas de la API de productos...\n\n";
        
        $this->testGetAllProducts();
        $this->testCreateProduct();
        
        if ($this->createdProductId) {
            $this->testGetProductById();
            $this->testUpdateProduct();
            $this->testDeleteProduct();
        }
        
        echo "\nPruebas completadas.\n";
    }
    
    /**
     * Prueba para obtener todos los productos
     */
    private function testGetAllProducts() {
        echo "TEST: Obtener todos los productos... ";
        
        $response = $this->makeRequest('products');
        
        if (is_array($response) && count($response) >= 0) {
            echo "PASÓ ✅\n";
            echo "  Productos encontrados: " . count($response) . "\n";
            return true;
        } else {
            echo "FALLÓ ❌\n";
            echo "  Error: No se pudo obtener la lista de productos\n";
            return false;
        }
    }
    
    /**
     * Prueba para crear un nuevo producto
     */
    private function testCreateProduct() {
        echo "TEST: Crear nuevo producto... ";
        
        $response = $this->makeRequest('products', 'POST', $this->testProduct);
        
        if (isset($response['id']) && $response['id'] > 0) {
            $this->createdProductId = $response['id'];
            echo "PASÓ ✅\n";
            echo "  Producto creado con ID: " . $this->createdProductId . "\n";
            return true;
        } else {
            echo "FALLÓ ❌\n";
            echo "  Error: No se pudo crear el producto\n";
            return false;
        }
    }
    
    /**
     * Prueba para obtener un producto por su ID
     */
    private function testGetProductById() {
        echo "TEST: Obtener producto por ID... ";
        
        $response = $this->makeRequest('products/' . $this->createdProductId);
        
        if (isset($response['id']) && $response['id'] == $this->createdProductId) {
            echo "PASÓ ✅\n";
            echo "  Nombre del producto: " . $response['name'] . "\n";
            return true;
        } else {
            echo "FALLÓ ❌\n";
            echo "  Error: No se pudo obtener el producto con ID " . $this->createdProductId . "\n";
            return false;
        }
    }
    
    /**
     * Prueba para actualizar un producto
     */
    private function testUpdateProduct() {
        echo "TEST: Actualizar producto... ";
        
        $updatedData = [
            'name' => $this->testProduct['name'] . ' (Actualizado)',
            'description' => $this->testProduct['description'] . ' - Versión actualizada',
            'price' => $this->testProduct['price'] + 100
        ];
        
        $response = $this->makeRequest('products/' . $this->createdProductId, 'PUT', $updatedData);
        
        if (isset($response['id']) && $response['name'] == $updatedData['name']) {
            echo "PASÓ ✅\n";
            echo "  Producto actualizado: " . $response['name'] . "\n";
            return true;
        } else {
            echo "FALLÓ ❌\n";
            echo "  Error: No se pudo actualizar el producto\n";
            return false;
        }
    }
    
    /**
     * Prueba para eliminar un producto
     */
    private function testDeleteProduct() {
        echo "TEST: Eliminar producto... ";
        
        $response = $this->makeRequest('products/' . $this->createdProductId, 'DELETE');
        
        // Verificar que el producto fue eliminado
        $checkResponse = $this->makeRequest('products/' . $this->createdProductId);
        
        if (isset($checkResponse['error']) || !isset($checkResponse['id'])) {
            echo "PASÓ ✅\n";
            echo "  Producto eliminado correctamente\n";
            return true;
        } else {
            echo "FALLÓ ❌\n";
            echo "  Error: El producto no fue eliminado\n";
            return false;
        }
    }
    
    /**
     * Función auxiliar para realizar peticiones a la API
     */
    private function makeRequest($endpoint, $method = 'GET', $data = null) {
        $url = API_URL . '/' . $endpoint;
        $ch = curl_init();
        
        $headers = [
            'Authorization: Bearer ' . API_TOKEN,
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
            if ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            }
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            echo 'Error cURL: ' . curl_error($ch) . "\n";
            return null;
        }
        
        curl_close($ch);
        
        return json_decode($response, true);
    }
}

// Ejecutar las pruebas
$tester = new ProductAPITest();
$tester->runTests(); 