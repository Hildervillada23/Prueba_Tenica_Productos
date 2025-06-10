<template>
  <div>
    <div v-if="loading" class="text-center py-8">
      <p class="text-lg">Cargando detalles del producto...</p>
    </div>
    <div v-else-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      <p>{{ error }}</p>
    </div>
    <div v-else-if="product" class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">{{ product.name }}</h2>
        <p class="text-gray-600 mb-4">{{ product.description }}</p>
        <p class="text-xl font-bold text-blue-600 mb-4">{{ product.price }}€</p>
        
        <div class="flex flex-wrap gap-3">
          <button 
            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition-colors"
            @click="addToCart"
          >
            Agregar al carrito
          </button>
          
          <router-link 
            :to="`/products/${productId}/edit`" 
            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded transition-colors"
          >
            Editar
          </router-link>
          
          <button 
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition-colors"
            @click="confirmDelete"
          >
            Eliminar
          </button>
          
          <router-link 
            to="/products" 
            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition-colors"
          >
            Volver a productos
          </router-link>
        </div>
      </div>
    </div>
    <div v-else class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
      <p>Producto no encontrado</p>
      <router-link 
        to="/products" 
        class="inline-block mt-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition-colors"
      >
        Volver a productos
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '../stores/productStore'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()

const loading = ref(true)
const error = ref(null)

const productId = computed(() => route.params.id)
const product = computed(() => productStore.getProductById(productId.value))

const addToCart = () => {
  alert(`Producto "${product.value.name}" agregado al carrito`)
}

const confirmDelete = async () => {
  if (confirm(`¿Estás seguro de que deseas eliminar el producto "${product.value.name}"?`)) {
    try {
      console.log(`Intentando eliminar producto con ID: ${productId.value}`);
      const result = await productStore.deleteProduct(productId.value);
      console.log('Resultado de eliminación:', result);
      router.push('/products');
    } catch (err) {
      console.error('Error en componente al eliminar:', err);
      error.value = err;
    }
  }
}

onMounted(async () => {
  loading.value = true
  try {
    console.log(`Cargando detalles para producto ID: ${productId.value}`);
    
    // Asegúrate de que los productos estén cargados
    if (productStore.products.length === 0) {
      console.log('Productos no cargados, cargando desde API...');
      await productStore.fetchProducts();
    }
    
    // Verificar si el producto existe
    const foundProduct = product.value;
    if (!foundProduct) {
      console.warn(`Producto con ID ${productId.value} no encontrado, intentando cargar de nuevo...`);
      
      // Intentar recargar todos los productos explícitamente
      await productStore.fetchProducts();
      
      // Verificar nuevamente
      if (!product.value) {
        console.error(`Producto con ID ${productId.value} no encontrado después de recargar`);
        error.value = `Producto con ID ${productId.value} no encontrado`;
      } else {
        console.log('Producto encontrado después de recargar:', product.value);
        loading.value = false;
      }
    } else {
      console.log('Producto encontrado:', foundProduct);
      loading.value = false;
    }
  } catch (err) {
    console.error('Error al cargar detalles del producto:', err);
    error.value = `Error al cargar detalles: ${err.message}`;
  } finally {
    loading.value = false;
  }
})
</script> 