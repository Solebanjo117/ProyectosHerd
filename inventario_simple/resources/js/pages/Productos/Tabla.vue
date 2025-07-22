<template>
  <div class="p-4">
    <table class="min-w-full bg-gray-800 border border-gray-600 rounded-lg overflow-hidden shadow-lg">
      <thead  class="bg-gray-700 border-b border-gray-600">
        <tr>
          <th class="text-center p-2">ID</th>
          <th class="text-center p-2">Nombre</th>
          <th class="text-center p-2">Descripción</th>
          <th class="text-center p-2">Precio</th>
          <th class="text-center p-2">Cantidad</th>
          <th class="text-center p-2">Categoría</th>
          <th class="text-center p-2">Código de Barras</th>
          <th class="text-center p-2">Imagen</th>
          <th class="text-center p-2">Activo</th>
          <th class="text-center p-2">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-600 ">
        <tr v-for="producto in props.productos" :key="producto.id" class="border-t hover:bg-gray-700 transition-colors">

          <td class="p-2">{{ producto.id }}</td>
          <td class="p-2">{{ producto.nombre }}</td>
          <td class="p-2">{{ producto.descripcion ?? '—' }}</td>
          <td class="p-2">${{ producto.precio.toFixed(2) }}</td>
          <td class="p-2">{{ producto.cantidad }}</td>
          <td class="p-2">{{ producto.categoria ?? '—' }}</td>
          <td class="p-2">{{ producto.codigo_barras ?? '—' }}</td>
          <td class="p-2">
            <button v-if="producto.imagen" @click="verImagen(producto.imagen)" class="text-blue-600 whitespace-nowrap hover:underline">
              Ver foto
            </button>
            <span v-else class="text-gray-400">Sin imagen</span>
          </td>

          <td class="p-2">
            <span class="px-2 py-1 rounded-full text-xs font-medium"
              :class="producto.activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
              {{ producto.activo ? 'Activo' : 'Inactivo' }}
            </span>
          </td>
          <td class="p-2">
            <button @click="router.get(`/productos/${producto.id}/edit`,{ },{ preserveScroll: false, preserveState: false })" class="text-blue-600 hover:underline">Editar</button>
          </td>
        </tr>
      </tbody>

    </table>


  </div>

</template>
<script setup lang="ts">
import { defineProps } from 'vue';

import { router } from '@inertiajs/vue3';
import type { Producto } from '@/types';


// Definir las props que espera el componente
const props = defineProps<{
  productos: Producto[];
}>();

function verImagen(url: string) {
  if (typeof window !== 'undefined') {
    window.open(url, '_blank');
  }
}
function editarProducto(producto: Producto) {
  console.log('Editar producto:', producto);
  // Aquí puedes emitir un evento o navegar con Inertia
}

</script>
