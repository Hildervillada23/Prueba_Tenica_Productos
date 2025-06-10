import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { API_URL, API_TOKEN } from '../config'

// Usar el token importado del archivo de configuración
// const API_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxIiwibmFtZSI6IlVzdWFyaW8gRGVtbyIsImVtYWlsIjoiZGVtb0BleGFtcGxlLmNvbSJ9.8Vo6IlPVMFIBTvHph2TYSLmI8UxXlE1xj7KptA7JIJ4';

export const useProductStore = defineStore('product', () => {
  const products = ref([])
  const loading = ref(false)
  const error = ref(null)
  
  // Getters
  const getAllProducts = computed(() => products.value)
  
  const getProductById = (id) => {
    // Convertir id a string para hacer la comparación más fiable
    const idStr = String(id);
    console.log(`Buscando producto con ID: ${idStr}`);
    console.log('Lista de productos:', products.value);
    
    // Buscar el producto comparando como strings
    const product = products.value.find(product => String(product.id) === idStr);
    
    if (product) {
      console.log('Producto encontrado:', product);
    } else {
      console.warn(`No se encontró el producto con ID ${idStr}`);
      console.log('IDs disponibles:', products.value.map(p => p.id));
    }
    
    return product;
  }
  
  // Fetch todos los productos
  const fetchProducts = async () => {
    loading.value = true;
    error.value = null;
    
    try {
      console.log('Cargando productos desde la API...');
      
      // Usar un retardo para asegurar que las solicitudes no se solapen
      await new Promise(resolve => setTimeout(resolve, 100));
      
      const response = await fetch(`${API_URL}/products`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'Cache-Control': 'no-cache, no-store'
        },
        mode: 'cors',
        cache: 'no-store'
      });
      
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Error ${response.status}: ${errorText}`);
      }
      
      const data = await response.json();
      console.log('Productos cargados:', data);
      
      // Convertir IDs a string para consistencia
      products.value = data.map(product => ({
        ...product,
        id: String(product.id)
      }));
      
      console.log('Productos procesados:', products.value);
    } catch (err) {
      console.error('Error al cargar productos:', err);
      error.value = err.message;
      products.value = [];
    } finally {
      loading.value = false;
    }
  };
  
  // Agregar un producto
  const addProduct = async (product) => {
    try {
      console.log('Enviando nuevo producto:', product);
      
      // Validar datos del producto
      if (!product.name || product.name.trim() === '') {
        throw new Error('El nombre del producto es obligatorio');
      }
      
      if (!product.description || product.description.trim() === '') {
        throw new Error('La descripción del producto es obligatoria');
      }
      
      if (!product.price || isNaN(product.price) || product.price <= 0) {
        throw new Error('El precio debe ser un número positivo');
      }
      
      // Usar un retardo para asegurar que las solicitudes no se solapen
      await new Promise(resolve => setTimeout(resolve, 100));
      
      const response = await fetch(`${API_URL}/products`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'Cache-Control': 'no-cache, no-store'
        },
        mode: 'cors',
        body: JSON.stringify(product)
      });
      
      // Asegurarse de leer la respuesta correctamente
      const responseText = await response.text();
      let responseData;
      
      try {
        responseData = JSON.parse(responseText);
      } catch (e) {
        responseData = { message: responseText };
      }
      
      if (!response.ok) {
        console.error('Respuesta de error:', responseData);
        throw new Error(`Error ${response.status}: ${responseData.message || responseText}`);
      }
      
      console.log('Producto agregado:', responseData);
      
      // Agregar el producto al estado local
      const newProduct = { 
        ...responseData,
        id: String(responseData.id)
      };
      products.value.unshift(newProduct);
      
      // Recargar todos los productos para asegurar consistencia
      await fetchProducts();
      
      return responseData.id;
    } catch (err) {
      console.error('Error al agregar producto:', err);
      error.value = err.message;
      throw error.value;
    }
  };
  
  // Actualizar un producto
  const updateProduct = async (id, product) => {
    try {
      const idStr = String(id);
      console.log(`Actualizando producto con ID ${idStr}`);
      console.log('Datos a enviar:', product);
      
      // Verificar que el producto existe antes de actualizarlo
      const existingProduct = getProductById(idStr);
      if (!existingProduct) {
        console.error(`Producto con ID ${idStr} no encontrado`);
        throw new Error(`Producto con ID ${idStr} no encontrado`);
      }
      
      // Usar un retardo para asegurar que las solicitudes no se solapen
      await new Promise(resolve => setTimeout(resolve, 100));
      
      const response = await fetch(`${API_URL}/products/${idStr}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'Cache-Control': 'no-cache, no-store'
        },
        mode: 'cors',
        body: JSON.stringify(product)
      });
      
      // Asegurarse de leer la respuesta correctamente
      const responseText = await response.text();
      let responseData;
      
      try {
        responseData = JSON.parse(responseText);
      } catch (e) {
        responseData = { message: responseText };
      }
      
      if (!response.ok) {
        console.error('Respuesta de error:', responseData);
        throw new Error(`Error ${response.status}: ${responseData.message || responseText}`);
      }
      
      console.log('Producto actualizado:', responseData);
      
      // Actualizar el producto en el estado local
      const index = products.value.findIndex(p => String(p.id) === idStr);
      if (index !== -1) {
        products.value[index] = { 
          ...responseData,
          id: String(responseData.id)
        };
      }
      
      // Recargar todos los productos para asegurar consistencia
      await fetchProducts();
      
      return true;
    } catch (err) {
      console.error('Error al actualizar producto:', err);
      error.value = err.message;
      throw error.value;
    }
  };
  
  // Eliminar un producto
  const deleteProduct = async (id) => {
    try {
      const idStr = String(id);
      console.log(`Eliminando producto con ID ${idStr}`);
      
      // Verificar que el producto existe antes de eliminarlo
      const existingProduct = getProductById(idStr);
      if (!existingProduct) {
        console.error(`Producto con ID ${idStr} no encontrado para eliminar`);
        throw new Error(`Producto con ID ${idStr} no encontrado para eliminar`);
      }
      
      // Usar un retardo para asegurar que las solicitudes no se solapen
      await new Promise(resolve => setTimeout(resolve, 100));
      
      const response = await fetch(`${API_URL}/products/${idStr}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${API_TOKEN}`,
          'Content-Type': 'application/json',
          'Cache-Control': 'no-cache, no-store'
        },
        mode: 'cors'
      });
      
      // Asegurarse de leer la respuesta correctamente
      const responseText = await response.text();
      let responseData;
      
      try {
        responseData = JSON.parse(responseText);
      } catch (e) {
        responseData = { message: responseText };
      }
      
      if (!response.ok) {
        console.error('Respuesta de error:', responseData);
        throw new Error(`Error ${response.status}: ${responseData.message || responseText}`);
      }
      
      console.log('Producto eliminado:', responseData);
      
      // Eliminar el producto del estado local
      products.value = products.value.filter(p => String(p.id) !== idStr);
      
      // Recargar todos los productos para asegurar consistencia
      await fetchProducts();
      
      return true;
    } catch (err) {
      console.error('Error al eliminar producto:', err);
      error.value = err.message;
      throw error.value;
    }
  };
  
  return {
    products,
    loading,
    error,
    getAllProducts,
    getProductById,
    fetchProducts,
    addProduct,
    updateProduct,
    deleteProduct
  }
})