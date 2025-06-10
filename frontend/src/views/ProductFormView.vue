<template>
  <div>
    <h2 class="text-2xl font-bold mb-6">{{ isEditing ? 'Editar Producto' : 'Agregar Nuevo Producto' }}</h2>
    
    <div v-if="loading" class="text-center py-8">
      <p class="text-lg">Cargando...</p>
    </div>
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <p>{{ error }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
      <form @submit.prevent="saveProduct" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
          <input 
            type="text" 
            id="name" 
            v-model="product.name" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required
          />
        </div>
        
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
          <textarea 
            id="description" 
            v-model="product.description" 
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            required
          ></textarea>
        </div>
        
        <div>
          <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
          <div class="relative mt-1 rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500 sm:text-sm">€</span>
            </div>
            <input 
              type="number" 
              id="price" 
              v-model="product.price" 
              step="0.01" 
              min="0"
              class="pl-7 w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>
        </div>
        
        <div class="flex space-x-4 pt-4">
          <button 
            type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors"
            :disabled="submitting"
          >
            {{ submitting ? 'Guardando...' : (isEditing ? 'Actualizar' : 'Guardar') }}
          </button>
          <router-link 
            to="/products" 
            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition-colors"
          >
            Cancelar
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useProductStore } from '../stores/productStore'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()

const loading = ref(false)
const error = ref(null)
const submitting = ref(false)

const productId = computed(() => route.params.id ? route.params.id : null)
const isEditing = computed(() => !!productId.value)

const product = ref({
  name: '',
  description: '',
  price: 0
})

onMounted(async () => {
  if (isEditing.value) {
    loading.value = true
    
    try {
      console.log(`Intentando editar producto con ID: ${productId.value}`);
      
      // Cargar productos si no están ya cargados
      if (productStore.products.length === 0) {
        console.log('No hay productos cargados, cargando...');
        await productStore.fetchProducts()
      }
      
      const existingProduct = productStore.getProductById(productId.value)
      console.log('Producto encontrado para editar:', existingProduct);
      
      if (existingProduct) {
        // Crear copia simple del producto
        product.value = {
          name: existingProduct.name || '',
          description: existingProduct.description || '',
          price: parseFloat(existingProduct.price) || 0
        }
        console.log('Datos cargados en el formulario:', product.value);
      } else {
        console.error(`Producto con ID ${productId.value} no encontrado`);
        error.value = `Producto con ID ${productId.value} no encontrado`
        alert(`Error: Producto con ID ${productId.value} no encontrado`)
        setTimeout(() => router.push('/products'), 2000)
      }
    } catch (err) {
      console.error('Error al cargar el producto:', err);
      error.value = 'Error al cargar el producto: ' + err
    } finally {
      loading.value = false
    }
  }
})

const saveProduct = async () => {
  submitting.value = true
  error.value = null
  
  try {
    // Validar datos
    if (!product.value.name || product.value.name.trim() === '') {
      throw new Error('El nombre del producto es obligatorio');
    }
    
    if (!product.value.description || product.value.description.trim() === '') {
      throw new Error('La descripción del producto es obligatoria');
    }
    
    if (!product.value.price || isNaN(product.value.price) || parseFloat(product.value.price) <= 0) {
      throw new Error('El precio debe ser un número positivo');
    }
    
    // Preparar datos para enviar
    const productData = {
      name: product.value.name.trim(),
      description: product.value.description.trim(),
      price: parseFloat(product.value.price)
    };
    
    console.log('Datos a guardar:', productData);
    
    if (isEditing.value) {
      // Actualizar producto existente
      console.log(`Actualizando producto con ID: ${productId.value}`);
      await productStore.updateProduct(productId.value, productData)
      alert('Producto actualizado correctamente. Regresando a la lista...')
    } else {
      // Crear nuevo producto
      console.log('Creando nuevo producto');
      const newId = await productStore.addProduct(productData)
      console.log(`Nuevo producto creado con ID: ${newId}`);
      alert('Producto creado correctamente. Regresando a la lista...')
    }
    
    // Recargar productos explícitamente
    await productStore.fetchProducts()
    
    // Redirigir a la lista de productos
    router.push('/products')
  } catch (err) {
    console.error('Error al guardar el producto:', err);
    error.value = 'Error al guardar el producto: ' + err
    alert('Error: ' + err)
  } finally {
    submitting.value = false
  }
}
</script> 