<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { Producto } from '@/types';
import Button from '@/components/ui/button/Button.vue';
const props = defineProps<{
  producto?: Producto;
}>();

const isEditando = computed(() => props.producto && props.producto.id !== undefined);

// Crea el formulario con datos del producto si existe
const form = useForm({
  nombre: props.producto?.nombre ?? '',
  descripcion: props.producto?.descripcion ?? '',
  precio: props.producto?.precio ?? 0,
  cantidad: props.producto?.cantidad ?? 0,
  categoria: props.producto?.categoria ?? '',
  codigo_barras: props.producto?.codigo_barras ?? '',
  imagen: null as File | null | undefined,
  activo: props.producto ? Boolean(Number(props.producto.activo)) : true,

});


// Acción al enviar
function guardar() {
   
  if (props.producto && props.producto.id !== undefined) {
    
    console.log(form.imagen);
    form.post(`/productos/${props.producto.id}/update`, {
      forceFormData: true, // Para enviar archivos correctamente
      
    });
  } else {
    console.log('Creando nuevo producto');
    form.post('/productos',{
      forceFormData: true,
    });
  }
}
</script>

<template>
  <form @submit.prevent="guardar"  class="space-y-4 p-4 border rounded shadow bg-dark mt-2 max-w-xl">
    <h2 class="text-lg font-bold">
      {{ isEditando ? 'Editar producto' : 'Crear producto' }}
    </h2>

    <div>
      <label class="block font-medium">Nombre</label>
      <input v-model="form.nombre" type="text" class="w-full border px-3 py-2 rounded" />
      <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
    </div>

    <div>
      <label class="block font-medium">Descripción</label>
      <textarea v-model="form.descripcion" class="w-full border px-3 py-2 rounded"></textarea>
    </div>

    <div class="flex gap-4">
      <div class="flex-1">
        <label class="block font-medium">Precio</label>
        <input v-model="form.precio" type="number" class="w-full border px-3 py-2 rounded" />
      </div>
      <div class="flex-1">
        <label class="block font-medium">Cantidad</label>
        <input v-model="form.cantidad" type="number" class="w-full border px-3 py-2 rounded" />
      </div>
    </div>

    <div>
      <label class="block font-medium">Categoría</label>
      <input v-model="form.categoria" type="text" class="w-full border px-3 py-2 rounded" />
    </div>

    <div>
      <label class="block font-medium">Código de barras</label>
      <input v-model="form.codigo_barras" type="text" class="w-full border px-3 py-2 rounded" />
    </div>

    <div>
      <label class="block font-medium">Imagen</label>
    <input
  type="file" accept="image/*"
  @change="e => form.imagen = (e.target as HTMLInputElement).files?.[0] ?? null"
/>


      <div v-if="form.errors.imagen" class="text-red-500 text-sm">{{ form.errors.imagen }}</div>
    </div>

    <div class="flex items-center gap-2">
      <input type="checkbox" v-model="form.activo" />
      <label>Activo</label>
    </div>

    <div class="w-full flex justify-center">
        <Button type="submit">
            {{ form.processing ? 'Guardando...' : 'Guardar' }}
        </Button>
    </div>
  </form>
</template>
