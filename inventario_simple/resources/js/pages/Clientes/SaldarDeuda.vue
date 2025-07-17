<script setup lang="ts">
//este componente es para saldar la deuda de un cliente, se mostrara en el formulario
//cuando el cliente tenga deuda mayor a 0, en la derecha del formulario de cliente
import { useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import type { Cliente } from '@/types';
const props = defineProps<{
  cliente: Cliente; //va a recibir el cliente al que se le va a saldar la deuda
}>();
const form = useForm({
  deuda: props.cliente.deuda, //deuda del cliente
    pago: 0, //cantidad a pagar
});
function guardar(){
form.put(`/clientes/${props.cliente.id}`,
    {
        onSuccess: () => {
        form.reset('pago'); //resetea el campo de pago al enviar el formulario
        },
        onError: (errors) => {
        console.error(errors); //manejo de errores, si hay errores se muestran en consola
        },
    }
); //actualiza el cliente con la deuda saldada y el pago realizado se envia al servidor
}
</script>
<template>
    
<form @submit.prevent="guardar" class="space-y-4 p-4 border rounded shadow bg-dark mt-2 max-w-xl">
    <h2 class="text-lg font-bold">Saldar deuda de {{ props.cliente.nombre }}</h2>
    
    <div>
      <label class="block font-medium">Deuda actual</label>
      <input v-model="form.deuda" type="number" readonly class="w-full border px-3 py-2 rounded bg-dark-200" />
    </div>

    <div>
      <label class="block font-medium">Pago</label>
      <input v-model="form.pago" type="number" min="0" step="0.01"
 :max="props.cliente.deuda" class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.pago" class="text-red-500 text-sm">{{ form.errors.pago }}</div>
    </div>

    <div class="w-full flex justify-center">
        <Button type="submit">
            {{ form.processing ? 'Saldando...' : 'Saldar deuda' }}
        </Button>
    </div>
</form>
</template>