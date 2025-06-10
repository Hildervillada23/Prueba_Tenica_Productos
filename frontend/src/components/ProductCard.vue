<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105">
    <div class="p-4">
      <h3 class="text-xl font-semibold mb-2">{{ product.name }}</h3>
      <p class="text-gray-600 mb-2">{{ product.description }}</p>
      <p class="text-lg font-bold text-blue-600">{{ product.price }}€</p>
      <div class="mt-4">
        <router-link 
          :to="`/products/${product.id}`" 
          class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded transition-colors"
        >
          Ver Detalles
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  }
})
</script> 