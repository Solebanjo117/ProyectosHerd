<!-- components/AutoTable.vue -->
<template>
  <table class="min-w-full bg-black border border-gray-300 rounded-lg overflow-hidden">
    <thead>
      <tr class="bg-black-100  text-white-700">
        <th v-for="col in finalColumns" :key="col.key" class="text-center p-2">
          {{ col.label }}
        </th>
        <th class="p-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, rowIndex) in data" :key="rowIndex">
        <td v-for="col in finalColumns" :key="col.key" class="p-2 max-w-36  text-center ">
           {{ get(item, col.key) }}
        </td>
        <td class="text-center"><button @click="router.get(`/${route}/${item.id}/edit`, {}, { preserveScroll: false, preserveState: false })"  class="text-blue-600 hover:underline ">Editar</button></td>
      </tr>
    </tbody>
  </table>
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
