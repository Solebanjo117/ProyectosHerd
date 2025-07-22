<!-- components/AutoTable.vue -->
<template>
  <div class="overflow-x-auto">
    <table class="min-w-full bg-gray-800 border border-gray-600 rounded-lg overflow-hidden shadow-lg">
      <thead>
        <tr class="bg-gray-700 border-b border-gray-600">
          <th v-for="col in finalColumns" :key="col.key" class="text-center p-4 text-xs font-medium text-gray-300 uppercase tracking-wider">
            {{ col.label }}
          </th>
          <th class="p-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-600">
        <tr v-for="(item, rowIndex) in data" :key="rowIndex" class="hover:bg-gray-700 transition-colors">
          <td v-for="col in finalColumns" :key="col.key" class="p-4 max-w-36 text-center text-sm text-gray-300">
             {{ get(item, col.key) }}
          </td>
          <td class="text-center p-4">
            <button 
              @click="router.get(`/${route}/${item.id}/edit`, {}, { preserveScroll: false, preserveState: false })"  
              class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-400 hover:text-blue-300 hover:bg-blue-900/20 rounded-lg transition-colors focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-gray-800"
            >
              Editar
            </button>
          </td>
        </tr>
        <tr v-if="data.length === 0">
          <td :colspan="finalColumns.length + 1" class="p-8 text-center text-gray-400 italic">
            No hay datos disponibles
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



<script setup lang="ts">
import { computed } from 'vue' // Importa computed de Vue para crear propiedades computadas
import { get } from 'lodash'; // Importa lodash para acceder a las propiedades de los objetos
import { router } from '@inertiajs/vue3';// Importa el router de Inertia.js para manejar la navegación
const props = defineProps<{
  data: Record<string, any>[],
   columns?: { key: string, label: string }[] // Opcional: si se pasan columnas personalizadas
   route ?: string // Ruta para las acciones, si se necesita
}>()

// Obtener columnas automáticamente de la primera fila
const finalColumns = computed(() => {
  // Si se pasaron columnas personalizadas, úsalas
  if (props.columns?.length) return props.columns

  // Si no, generar por defecto usando las claves del primer objeto
  return props.data.length > 0
    ? Object.keys(props.data[0]).map(key => ({ key, label: key }))
    : []
})
</script>
