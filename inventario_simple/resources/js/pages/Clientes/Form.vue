
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import SaldarDeuda from './SaldarDeuda.vue';
import Button from '@/components/ui/button/Button.vue';
import type { Cliente } from '@/types';
const props = defineProps<{
  cliente?: Cliente;
}>();
const isEditando = computed(() => props.cliente && props.cliente.id !== undefined);
const form = useForm({
  nombre: props.cliente?.nombre ?? '',
  correo: props.cliente?.correo ?? '',
  telefono: props.cliente?.telefono ?? '',
  direccion: props.cliente?.direccion ?? '', 
  deuda: props.cliente?.deuda ?? 0,
});
function guardar() {
  if (props.cliente && props.cliente.id !== undefined) {
    form.put(`/clientes/${props.cliente.id}`);
  } else {
    form.post('/clientes');
  }
}
</script>
<template>
<form @submit.prevent="guardar" class="space-y-4 p-4 border rounded shadow bg-dark mt-2 max-w-xl">
    <h2 class="text-lg font-bold">
      {{ isEditando ? 'Editar cliente' : 'Crear cliente' }}
    </h2>

     <div>
      <label class="block font-medium">Nombre</label>
      <input v-model="form.nombre" type="text" class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
    </div>

     <div>
      <label class="block font-medium">Correo</label>
      <input v-model="form.correo" type="email" class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.correo" class="text-red-500 text-sm">{{ form.errors.correo }}</div>
    </div>

    <div>
      <label class="block font-medium">Numero de telefono</label>
      <input v-model="form.telefono" type="text"  class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.telefono" class="text-red-500 text-sm">{{ form.errors.telefono }}</div>
    </div>

    <div>
      <label class="block font-medium">Direccion</label>
      <input v-model="form.direccion" type="text"  class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.direccion" class="text-red-500 text-sm">{{ form.errors.direccion }}</div>
    </div>
    
    <div class="w-full flex justify-center">
        <Button type="submit">
            {{ form.processing ? 'Guardando...' : 'Guardar' }}
        </Button>
    </div>
    

</form>
<SaldarDeuda v-if="props.cliente && props.cliente.deuda > 0" :cliente="props.cliente" class="ml-4" />
</template>