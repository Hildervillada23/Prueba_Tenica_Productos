<template>
  <div class="min-h-screen bg-gray-100">
    <header class="bg-blue-600 text-white p-4 shadow-md">
      <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Mi Catálogo de Productos</h1>
        <div v-if="productStore.user">
          <span class="mr-2">{{ productStore.user.name }}</span>
        </div>
      </div>
    </header>
    <main class="container mx-auto p-4">
      <router-view />
    </main>
    <footer class="bg-gray-800 text-white p-4 mt-auto">
      <div class="container mx-auto text-center">
        <p>&copy; 2025 Mi Catálogo de Productos. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useProductStore } from './stores/productStore';

const productStore = useProductStore();

onMounted(async () => {
  // Auto-login con usuario predefinido al cargar la aplicación
  await productStore.autoLogin();
});
</script> 