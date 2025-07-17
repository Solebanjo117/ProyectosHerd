<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineProps,watch } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import type { Entrada, EntradaDetalle } from '@/types';
import axios from 'axios';
const props = defineProps<{
  entrada?: Entrada;
}>();

const isEditando = computed(() => props.entrada && props.entrada.id !== undefined);

const form = useForm({
  referencia: props.entrada?.referencia ?? '',
  tipo: props.entrada?.tipo ?? '',
  observaciones: props.entrada?.observaciones ?? '',
  detalles: props.entrada?.detalles?.map(detalle => ({
    entrada_id: detalle.entrada_id,
    codigo_barras: detalle.producto?.codigo_barras ?? '',
    producto_id: detalle.producto_id,
    producto_nombre: detalle.producto.nombre ?? '',
    cantidad: detalle.cantidad,
    precio_unitario: detalle.precio_unitario,
    created_at: detalle.created_at,
    updated_at: detalle.updated_at,
  })) ?? [],
});

function agregarDetalle() {
  form.detalles.push({
    entrada_id: props.entrada?.id ?? 0,
    codigo_barras: '',
    producto_id: 0,
    producto_nombre: '',
    cantidad: 1,
    precio_unitario: 0,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  });
}

function guardar() {
  if (props.entrada?.id !== undefined) {
    form.put(`/entradas/${props.entrada.id}`);
  } else {
    form.post('/entradas');
  }
}
async function buscarProductoPorCodigo(codigo: string, index: number) {
  try {
    const response = await axios.get(`/productos/${codigo}`);
    const producto = response.data;
    form.detalles[index].producto_nombre = producto.nombre;
    form.detalles[index].producto_id = producto.producto_id;
  } catch (error) {
    form.detalles[index].producto_nombre = 'No encontrado';
    form.detalles[index].producto_id = 0;
  }
}
// ✅ Detectar cuando cambie el código de barras
watch(
  () => form.detalles.map(d => d.codigo_barras),
  (nuevosCodigos, previosCodigos) => {
    nuevosCodigos.forEach((codigo, i) => {
      if (codigo && codigo !== previosCodigos[i]) {
        buscarProductoPorCodigo(codigo, i);
      }
    });
  }
);
</script>

<template>
  <form @submit.prevent="guardar" class="space-y-4 p-4 border rounded shadow bg-dark mt-2 max-w-xl">
    <h2 class="text-lg font-bold">
      {{ isEditando ? 'Editar entrada' : 'Crear entrada' }}
    </h2>

    <!-- Campos generales -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <div>
        <label class="block text-sm font-medium text-white-700">Referencia</label>
        <input v-model="form.referencia" type="text" class="mt-1 block w-full border rounded p-2" />
      </div>

      <div>
        
        <label class="block text-sm font-medium text-white-700">Tipo</label>
        <!-- <input v-model="form.tipo" type="text" class="mt-1 block w-full border rounded p-2" />-->
         <select v-model="form.tipo" required class="mt-1 block w-full rounded border   text-white px-3 py-2 focus:ring focus:ring-blue-500 focus:border-blue-500">
            <option value=""  disabled selected>Seleccione un tipo</option>
            <option value="Manual" >Manual</option>
            <option value="Traslado">Traslado</option>
            <option value="Donacion">Donacion</option>
            <option value="otro">Otro</option>
        </select>
      <div v-if="form.errors.tipo" class="text-red-500 text-sm">{{ form.errors.tipo }}</div>
      
    </div>

      <div>
        <label class="block text-sm font-medium text-white-700">Observaciones</label>
        <textarea v-model="form.observaciones" class="mt-1 block w-full border rounded p-2"></textarea>
      </div>
    </div>

    <!-- Tabla de detalles -->
    <div>
      <h2 class="text-lg font-semibold mb-2">Detalles</h2>
      <div v-if="form.errors.detalles" class="text-red-500 text-sm">{{ form.errors.detalles }}</div>
      <table class="w-full border text-sm">
        <thead class="bg-dark-100">
          <tr>
            <th class="border px-2 py-1">Código de Barras</th>
            <th class="border px-2 py-1">Producto</th>
            <th class="border px-2 py-1">Cantidad</th>
            <th class="border px-2 py-1">Precio Unitario</th>
            <th class="border px-2 py-1">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(detalle, index) in form.detalles" :key="index"  >
            <td class="border px-2 py-1">
              <input v-model="detalle.codigo_barras" type="text" class="w-full p-1 border rounded" />
            </td>
            <td class="border px-2 py-1">
              <input v-model="detalle.producto_nombre" readonly type="text" class="w-full p-1 border rounded" />
            </td>
            <td class="border px-2 py-1">
              <input v-model="detalle.cantidad" type="number" class="w-full p-1 border rounded" />
            </td>
            <td class="border px-2 py-1">
              <input v-model="detalle.precio_unitario" type="number" step="0.01" class="w-full p-1 border rounded" />
            </td>
            <td class="border px-2 py-1 text-center">
              <button type="button" @click="form.detalles.splice(index, 1)" class="text-red-600 hover:underline">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>

      <button type="button" @click="agregarDetalle" class="mt-2 px-4 py-1 bg-blue-500 text-white rounded">+ Agregar Detalle</button>
    </div>

    <!-- Botón de envío -->
    <div class="w-full flex justify-center">
      <Button type="submit">
        {{ form.processing ? 'Guardando...' : 'Guardar' }}
      </Button>
    </div>
  </form>
</template>
