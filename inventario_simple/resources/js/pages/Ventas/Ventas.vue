<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { Cliente, Producto } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import SearchAutocomplete from './SearchAutocomplete.vue';
import axios from 'axios';
import Button from '@/components/ui/button/Button.vue';
import { Trash2 } from 'lucide-vue-next';
import { defineProps, ref, computed } from 'vue';
const breadcrumbs = [{ title: 'Ventas', href: '/ventas' }];
const form = useForm({
    cliente_id: null,
    productos: [] as {
        producto_id: number; codigo_barras: string;
        nombre: string; cantidad: number; precio_unitario: number; total: number
    }[],
});
function guardar(){
    if(verify())  form.post('/ventas',
{
        onSuccess: () => {
            form.reset();
            recibido.value = 0;
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
    else alert('El dinero recibido no es suficiente para cubrir el total de la venta.');
}
const props = defineProps<{
    clientes: Cliente[];
}>();
function handleSelect(opcion: any) {
    form.cliente_id = opcion.id;
}
const recibido = ref(0);
const total = computed(() =>
    form.productos.reduce((suma, prod) => suma + (prod.cantidad * prod.precio_unitario), 0));
    function verify(){
        return recibido.value - parseInt(total.value.toFixed(2))>=0;
    }
async function agregarProducto(evento: KeyboardEvent) {
    try {
        const input = (evento.target as HTMLInputElement);
        const codigo = input.value.trim().replace(/\s/g, '');
        if (!codigo) return;

        const data = (await axios.get(`/productos/${codigo}`)).data;
        input.value = '';
        addRow(data);
    } catch (err) {
        console.log(err);
    }
}
function addRow(data: any) {

    if (form.productos.some(x => x.codigo_barras == data.codigo_barras)) {
        form.productos.filter(x => x.codigo_barras == data.codigo_barras)[0].cantidad++;
    } else
        form.productos.push({
            producto_id: data.producto_id,
            nombre: data.nombre,
            cantidad: 1,
            precio_unitario: data.precio,
            total: 0,
            codigo_barras: data.codigo_barras
        });
}
</script>
<template>
  <Head title="Ventas" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <form @submit.prevent="guardar" class="p-6 max-w-7xl mx-auto">
      <div class="bg-gray-800 rounded-lg shadow-2xl overflow-hidden border border-gray-700">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 px-6 py-4 border-b border-gray-700">
          <h2 class="text-2xl font-bold text-white text-center">Sistema de Ventas</h2>
        </div>

        <!-- Form Content -->
        <div class="p-6 space-y-6">
          <!-- Input Section -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Código de Barras -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-300">
                Código de Barras
              </label>
              <input 
                type="text" 
                @keydown.enter.prevent="agregarProducto" 
                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-colors text-white placeholder-gray-400"
                placeholder="Escanea o ingresa el código de barras"
              >
            </div>

            <!-- Cliente -->
            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-300">
                Cliente
              </label>
              <SearchAutocomplete :datos="clientes" @select="handleSelect" />
            </div>
          </div>

          <!-- Tabla de Productos -->
          <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
            <div class="overflow-x-auto">
              <table class="w-full bg-gray-800 border border-gray-600 rounded-lg overflow-hidden shadow-lg">
                <thead>
                  <tr class="bg-gray-700 border-b border-gray-600">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Código
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Producto
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Cantidad
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Precio Unit.
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Total
                    </th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                      Acción
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                  <tr v-for="producto, index in form.productos" :key="producto.producto_id" 
                      class="hover:bg-gray-700 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-300">
                      {{ producto.codigo_barras }}
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-white">
                      {{ producto.nombre }}
                    </td>
                    <td class="px-4 py-3 text-center">
                      <input 
                        type="number" 
                        @keydown.enter.prevent="" 
                        v-model.number="producto.cantidad" 
                        class="w-20 px-2 py-1 text-center bg-gray-700 border border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-blue-400 text-white" 
                        min="1"
                      >
                    </td>
                    <td class="px-4 py-3 text-center">
                      <input 
                        type="number" 
                        @keydown.enter.prevent="" 
                        v-model.number="producto.precio_unitario" 
                        step="0.01"
                        class="w-24 px-2 py-1 text-center bg-gray-700 border border-gray-600 rounded focus:ring-1 focus:ring-blue-400 focus:border-blue-400 text-white" 
                        min="1"
                      >
                    </td>
                    <td class="px-4 py-3 text-center text-sm font-semibold text-green-400">
                      ${{ (producto.cantidad * producto.precio_unitario).toFixed(2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                      <button 
                        @click="form.productos.splice(index, 1)"
                        class="inline-flex items-center justify-center p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                        type="button"
                      >
                        <Trash2 class="w-4 h-4" />
                      </button>
                    </td>
                  </tr>
                  <tr v-if="form.productos.length === 0">
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 italic">
                      No hay productos agregados
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Totales Section -->
          <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-lg p-6 border border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Subtotal -->
              <div class="bg-gray-800 rounded-lg p-4 shadow-lg border border-gray-600">
                <div class="text-center">
                  <div class="text-sm font-medium text-gray-400 uppercase">Subtotal</div>
                  <div class="text-2xl font-bold text-white">${{ total.toFixed(2) }}</div>
                </div>
              </div>

              <!-- Dinero Recibido -->
              <div class="bg-gray-800 rounded-lg p-4 shadow-lg border border-gray-600">
                <div class="text-center space-y-2">
                  <label class="block text-sm font-medium text-gray-400 uppercase">
                    Dinero Recibido
                  </label>
                  <input 
                    @keydown.enter.prevent="" 
                    class="w-full px-3 py-2 text-center text-xl font-bold bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-white placeholder-gray-400" 
                    v-model="recibido" 
                    type="number" 
                    min="1"
                    placeholder="0.00"
                  >
                </div>
              </div>

              <!-- Cambio -->
              <div class="bg-green-900 rounded-lg p-4 shadow-lg border border-green-700">
                <div class="text-center">
                  <div class="text-sm font-medium text-green-400 uppercase">Cambio</div>
                  <div class="text-2xl font-bold text-green-300">
                    ${{  recibido - parseInt(total.toFixed(2))  }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-center pt-4">
            <Button 
              class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 focus:ring-4 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-gray-800" 
              type="submit"
            >
              <span class="flex items-center space-x-2">
                <span>Completar Venta</span>
              </span>
            </Button>
          </div>
        </div>
      </div>
    </form>
  </AppLayout>
</template>