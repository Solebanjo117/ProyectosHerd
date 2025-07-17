<template>
    
  <div class="relative w-full max-w-md mx-auto ">
    <input
      v-model="searchQuery"
      type="text"
      @keypress="(event) => {
        if (event.key === 'Enter') {
          performSearch();
        }
      }"
      
      placeholder="Buscar..."
      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white dark:border-gray-700"
    />
    <Button
      @click="performSearch"
      class="absolute right-2 top-1/2 transform -translate-y-1/2 hover:cursor-pointer"
    >
      Buscar
    </Button>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import { defineProps } from 'vue';

import { router } from '@inertiajs/vue3';
const searchQuery = ref('');
const props = defineProps<{
  initialSearch: string;
}>();
function performSearch() {
   
  // Si hay texto, redirigir a la ruta de búsqueda con el query
    // y mantener el scroll y el estado de la página
    // usando preserveScroll y preserveState
    // y reemplazar la entrada en el historial para no acumular búsquedas
    // (esto es útil para evitar que el usuario navegue hacia atrás en el historial de búsquedas)

  router.get('/'.concat(props.initialSearch), { search: searchQuery.value }, {
    preserveScroll: true,
    preserveState: true,
    replace: true, // para no guardar en historial cada búsqueda
  });
}   
</script>