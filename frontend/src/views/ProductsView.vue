<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">Nuestros Productos</h2>
      <router-link
        to="/products/new"
        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors"
      >
        Agregar Producto
      </router-link>
    </div>
    
    <div v-if="productStore.loading" class="text-center py-8">
      <p class="text-lg">Cargando productos...</p>
    </div>
    <div v-else-if="productStore.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
      <p>{{ productStore.error }}</p>
    </div>
    <div v-else-if="productStore.products.length === 0" class="text-center py-8">
      <p class="text-lg">No hay productos disponibles. ¡Agrega algunos!</p>
    </div>
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="product in productStore.getAllProducts" 
        :key="product.id" 
        class="bg-white rounded-lg shadow-md overflow-hidden"
      >
        <div class="p-4">
          <h3 class="text-xl font-semibold mb-2">{{ product.name }}</h3>
          <p class="text-gray-600 mb-2">{{ product.description }}</p>
          <p class="text-lg font-bold text-blue-600 mb-2">{{ product.price }}€</p>
          <div class="mt-4 flex space-x-2">
            <router-link 
              :to="`/products/${product.id}`" 
              class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded transition-colors"
            >
              Ver
            </router-link>
            <router-link 
              :to="`/products/${product.id}/edit`" 
              class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded transition-colors"
            >
              Editar
            </router-link>
            <button 
              @click="confirmDelete(product)"
              class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded transition-colors"
            >
              Eliminar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, onBeforeMount, onActivated } from 'vue'
import { useProductStore } from '../stores/productStore'

const productStore = useProductStore()
const error = ref(null)
const lastRefresh = ref(Date.now())

// Función para recargar datos desde cero
const refreshProducts = async () => {
  // Evitar recargas frecuentes en menos de 1 segundo
  const now = Date.now();
  if (now - lastRefresh.value < 1000) {
    console.log('Evitando recarga frecuente');
    return;
  }
  
  lastRefresh.value = now;
  
  try {
    console.log('Forzando recarga completa de productos...');
    // Primero vaciamos la lista para evitar problemas de estado anterior
    productStore.products = [];
    // Luego cargamos los productos frescos
    await productStore.fetchProducts();
    console.log('Productos recargados completamente:', productStore.products);
  } catch (err) {
    console.error('Error al recargar productos:', err);
    error.value = err;
  }
}

// Cargar al montar el componente
onMounted(async () => {
  console.log('ProductsView montado, cargando productos...');
  await refreshProducts();
})

// También recargar cuando se visita la ruta
onBeforeMount(async () => {
  console.log('ProductsView será montado, preparando carga...');
  await refreshProducts();
})

// Recargar cuando el componente se reactiva (al navegar de vuelta)
onActivated(async () => {
  console.log('ProductsView activado, recargando productos...');
  await refreshProducts();
})

const confirmDelete = async (product) => {
  if (confirm(`¿Estás seguro de que deseas eliminar el producto "${product.name}"?`)) {
    try {
      console.log(`Eliminando producto con ID: ${product.id}`);
      await productStore.deleteProduct(product.id);
      alert('Producto eliminado correctamente');
      // Recargar productos después de eliminar
      await refreshProducts();
    } catch (err) {
      console.error('Error al eliminar producto:', err);
      alert(`Error al eliminar: ${err}`);
    }
  }
}
</script> 