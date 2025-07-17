<script setup lang="ts">
import { InertiaForm, useForm } from '@inertiajs/vue3';
import { ref, defineProps, watch } from 'vue';
import type { SalidaDetalle } from '@/types';
import axios from 'axios';
import Button from '@/components/ui/button/Button.vue';
const props = defineProps<{
  salidaDetalle: SalidaDetalle[];
  formulario?: InertiaForm<any>; // Formulario para crear  la salida cuando es nueva, no se usa en edición
  salida_id: number; // ID de la salida si se está editando
}>();
const salidaDetalle = ref<SalidaDetalle[]>([...props.salidaDetalle])

const form = useForm({
    salida_id: props.salida_id, // Asumiendo que todos los detalles pertenecen a la misma salida
   detalles: salidaDetalle.value.map(detalle => ({
        id: detalle.id || null, // Si es una edición, se usa el ID existente o null si es nuevo
        producto_id: detalle.producto_id,
        cantidad: detalle.cantidad,
        precio_unitario: detalle.precio_unitario,
    })),
    formulario: props.formulario || null, // Formulario para crear la salida si es necesario
});
function agregarDetalle() {
  salidaDetalle.value.push({
    id: 0, // Nuevo detalle, no tiene ID aún
    salida_id: 0, // Asumiendo que todos los detalles pertenecen a la misma salida
    producto: {
      codigo_barras: '', nombre: '',
      id: 0,
      descripcion: '',
      precio: 0,
      cantidad: 0,
      activo: false,
      created_at: '',
      updated_at: ''
    }, // Producto vacío inicialmente
    producto_id: 0,
    cantidad: 1,
    precio_unitario: 0,
    created_at: '', // Agregar propiedad faltante
    updated_at: '', // Agregar propiedad faltante
  });
  form.detalles.push({
    id: null, // Nuevo detalle, no tiene ID aún
    producto_id: 0,
    cantidad: 1,
    precio_unitario: 0,
  });
}
function guardar() {
  if (props.salida_id !== 0) {
    form.put(`/salidasDetalle/${form.salida_id}`);
  } else {
    // Si es una nueva salida, se envían los detalles al crear la salida
    // Aquí podrías enviar el formulario principal si es necesario
    // Por ejemplo, si tienes un formulario para crear la salida, podrías hacer algo como:
    // props.formulario.post('/salidas', { detalles: form.detalles });
    // Pero si solo necesitas los detalles, puedes hacer lo siguiente:

    form.post('/salidasDetalle');
  }
}
const eliminarDetalle = (index:number) => {
  form.detalles.splice(index, 1)
  salidaDetalle.value.splice(index, 1)
}
async function buscarProductoPorCodigo(codigo: string, index: number) {
  try {
    const response = await axios.get(`/productos/${codigo}`);
    const producto = response.data;
    console.log(producto);
     if (salidaDetalle.value[index] && salidaDetalle.value[index].producto) {
      salidaDetalle.value[index].producto.nombre = producto.nombre;
    }
    form.detalles[index].producto_id = producto.producto_id;
  } catch (error) {
    if (salidaDetalle.value[index] && salidaDetalle.value[index].producto) {
      salidaDetalle.value[index].producto.nombre = 'No encontrado';
    }
    form.detalles[index].producto_id = 0;
  }
}
// ✅ Detectar cuando cambie el código de barras

watch(
  () => salidaDetalle.value.map(det => det.producto?.codigo_barras),
  (nuevosCodigos, antiguosCodigos) => {
    nuevosCodigos.forEach((codigo, index) => {
      if (codigo && codigo !== antiguosCodigos[index]) {
        buscarProductoPorCodigo(codigo, index)
      }
    })
  }
)

</script>
<template>
  <form @submit.prevent="guardar" class="p-4 border rounded shadow bg-dark mt-2">
  <h2 class="text-lg font-bold text-center mb-4">Detalles de la Salida</h2>
    <div v-if="form.errors.detalles" class="text-red-500 text-sm">{{ form.errors.detalles }}</div>
  <table class="w-full table-auto border-collapse text-sm bg-black rounded-md overflow-hidden">
    <thead class="bg-black-200">
      <tr>
        <th class="px-4 py-2 text-left">Código de Barras</th>
        <th class="px-4 py-2 text-left">Nombre del Producto</th>
        <th class="px-4 py-2 text-left">Cantidad</th>
        <th class="px-4 py-2 text-left">Precio Unitario</th>
        <th class="px-4 py-2 text-left">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(detalle, index) in form.detalles" :key="index" class="border-b">
        <td class="px-4 py-2">
          <span class="text-red-500 text-sm" v-if="(form.errors as any)[`detalles.${index}.producto_id`]">
  Debe colocar un codigo de barras existente 
</span>
         <input v-model="salidaDetalle[index].producto.codigo_barras" type="text" placeholder="Código de barras" required
       class="w-full p-2 border border-gray-300 rounded-md" />
        </td>
        <td class="px-4 py-2">
         <input :value="salidaDetalle[index].producto?.nombre" type="text" readonly placeholder="Nombre del producto"
       class="w-full p-2 border border-gray-300 rounded-md" /> </td>
        <td class="px-4 py-2">
          <input v-model="detalle.cantidad" type="number" placeholder="Cantidad" required
                 class="w-full p-2 border border-gray-300 rounded-md" />
        </td>
        <td class="px-4 py-2">
          <input v-model="detalle.precio_unitario" type="number" placeholder="Precio Unitario" required
                 class="w-full p-2 border border-gray-300 rounded-md" />
        </td>
        <td class="px-4 py-2 text-center">
          <button type="button" @click="eliminarDetalle(index)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            Eliminar
          </button>
        </td>
      </tr>
    </tbody>
  </table>
   <div class="text-left mt-4">
    <Button type="button" @click="agregarDetalle" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      +Agregar Detalle
    </Button>
  </div>
 
  <div class="text-center mt-4">
    <Button type="submit">
      Guardar Detalles
    </Button>
  </div>
</form>

</template>