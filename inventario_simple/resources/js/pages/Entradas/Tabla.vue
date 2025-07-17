<script setup lang="ts">
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { type Entrada } from '@/types';
const props = defineProps<{
  entradas: Entrada[];
}>();
</script>
<template>
    <div class="p-4">
      <table class="min-w-full bg-black border border-gray-300 rounded-lg overflow-hidden">
 <thead class="bg-black-100  text-white-700">
   
            <tr>
            <th class="text-center p-2">ID</th>
            <th class="text-center p-2">Referencia</th>
            <th class="text-center p-2">Tipo</th>
            <th class="text-center p-2">Observaciones</th>
            <th class="text-center p-2">Fecha</th>
            <th class="text-center p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="entrada in props.entradas" :key="entrada.id" class="border-t">
            <td class="p-2 text-center">{{ entrada.id }}</td>
            <td class="p-2 text-center">{{ entrada.referencia }}</td>
            <td class="p-2 text-center">{{ entrada.tipo }}</td>
            <td class="p-2 text-center">{{ entrada.observaciones }}</td>
            <td class="p-2 text-center">{{ new Date(entrada.created_at).toLocaleString() }}</td>
            <td class="p-2 text-center">
                <button @click="router.get(`/entradas/${entrada.id}/edit`, {}, { preserveScroll: false, preserveState: false })" class="text-blue-600 hover:underline">Editar</button>
            </td>
            </tr>
        </tbody>
        </table>
    </div>
</template>