<template>
<div class="p-4">
     <table class="min-w-full bg-black border border-gray-300 rounded-lg overflow-hidden">
 <thead class="bg-black-100  text-white-700">
        <tr>
          <th class="text-center p-2">ID</th>
          <th class="text-center p-2">Nombre</th>
          <th class="text-center p-2">Telefono</th>
          <th class="text-center p-2">Correo</th>
          <th class="text-center p-2">Direccion</th>
          <th class="text-center p-2">Deuda</th>
          <th class="text-center p-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cliente in props.clientes" :key="cliente.id" class="border-t">
          <td class="p-2">{{ cliente.id }}</td>
          <td class="p-2">{{ cliente.nombre }}</td>
          <td class="p-2">{{ cliente.telefono ?? '—' }}</td>
          <td class="p-2">{{ cliente.correo ?? '—' }}</td>
          <td class="p-2">{{ cliente.direccion ?? '—' }}</td>
          <td class="p-2">${{ cliente.deuda.toFixed(2) }}</td>
          <td class="p-2">
            <button @click="router.get(`/clientes/${cliente.id}/edit`,{ },{ preserveScroll: false, preserveState: false })" class="text-blue-600 hover:underline">Editar</button>
          </td>
        </tr>
      </tbody>
</table>
</div>
</template>
<script setup lang="ts">
import { defineProps } from 'vue';
import {router} from '@inertiajs/vue3';
import type { Cliente } from '@/types';
const props = defineProps<{
  clientes: Cliente[];
}>();
</script>
