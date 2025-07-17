<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed,ref } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import type { Salida,Cliente } from '@/types';
import SalidasDetalle from './SalidasDetalle.vue';
const props = defineProps<{
  salida?: Salida;
  clientes?: Cliente[]; // Lista de clientes para el formulario
}>();
const mostrar =ref(false); // Controla la visibilidad del formulario
const isEditando = computed(() => props.salida && props.salida.id !== undefined);
const form = useForm({
    id: props.salida?.id || null, // Si es una edición, se usa el ID existente o null si es nuevo
    tipo: props.salida?.tipo || 'venta', // Por defecto 'venta' si no se especifica
    cliente_id: props.salida?.cliente_id || null,
    estado: props.salida?.estado || 'pendiente', // Por defecto 'pendiente'
    observaciones: props.salida?.observaciones || '',
    referencia: props.salida?.referencia || '',
});
function guardar() {
  if (props.salida && props.salida.id !== undefined) {
    form.put(`/salidas/${props.salida.id}`);
  } else {
    form.post('/salidas');
  }
}
</script>
<template>
   <div class="flex flex-col items-center justify-center p-4  rounded-lg shadow-lg">
     <form @submit.prevent="guardar" class="space-y-4 flex-1 p-4 border rounded shadow bg-dark mt-2 max-w-xl">
        <h2 class="text-lg font-bold justify-center text-center mb-4">
            {{ isEditando ? 'Editar salida' : 'Crear salida' }}
        </h2>
        <div class="mb-4 flex ">
            <div class="flex-1 mr-2">
                <label for="tipo" class="block text-sm font-medium text-white-700">Tipo</label>
            <select v-model="form.tipo" required id="tipo" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                <option value="" disabled selected >Seleccione una opcion</option>
                <option value="venta">Venta</option>
                <option value="devolucion">Devolución</option>
                <option value="otro">Otro</option>
            </select>
            <div v-if="form.errors.tipo" class="text-red-500 text-sm">{{ form.errors.tipo }}</div>
            </div>
            <div class="flex-2 mr-2">
                <label for="cliente_id" class="block text-sm font-medium text-white-700">Cliente</label>
                <select v-model="form.cliente_id" id="cliente_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value=""  selected >Seleccione un cliente</option>
                    <option :selected="props.salida?.cliente_id == cliente.id" v-for="cliente in props.clientes" :key="cliente.id" :value="cliente.id">
                        {{ cliente.nombre }}
                    </option>
                </select>
                <div v-if="form.errors.cliente_id" class="text-red-500 text-sm">{{ form.errors.cliente_id }}</div>
            </div>
        </div>
        <div class="mb-4 flex">
            <div class="flex-2 mr-2">
                <label for="estado" class="block text-sm font-medium text-white-700">Estado</label>
            <select v-model="form.estado" required id="estado" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                <option value="" disabled selected >Seleccione un estado</option>
                <option value="pendiente">Pendiente</option>
                <option value="completado">Completado</option>
                <option value="cancelado">Cancelado</option>
            </select>
            <div v-if="form.errors.estado" class="text-red-500 text-sm">{{ form.errors.estado }}</div>
            </div>
            <div class="flex-3 mr-2">
                <label for="referencia" class="block text-sm font-medium text-white-700">Referencia</label>
                <input v-model="form.referencia" type="text" id="referencia" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Referencia (opcional)">
                <div v-if="form.errors.referencia" class="text-red-500 text-sm">{{ form.errors.referencia }}</div>
            </div>
            <div class="flex-3">
                <label for="observaciones" class="block text-sm font-medium text-white-700">Observaciones</label>
                <textarea v-model="form.observaciones" id="observaciones"  class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Observaciones (opcional)"></textarea>
                <div v-if="form.errors.observaciones" class="text-red-500 text-sm">{{ form.errors.observaciones }}</div>
            </div>
        </div>
       <div class="flex justify-center gap-2">
        <Button type="button"  @click="mostrar = !mostrar" >
            {{ mostrar ? 'Ocultar detalles' : 'Mostrar detalles' }}
        </Button>
       </div>
    </form>
    <SalidasDetalle class="flex-3" v-if="mostrar" :formulario="form" :salida_id="props.salida?.id || 0" :salidaDetalle="props.salida?.detalles || []"></SalidasDetalle>
   </div>
        
</template>