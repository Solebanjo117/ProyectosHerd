<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type{ Cliente,Producto } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import SearchAutocomplete from './SearchAutocomplete.vue';
import axios from 'axios';
import { debounce } from 'lodash';
import { Trash2 } from 'lucide-vue-next';
import { computed,defineProps, ref, watch,nextTick  } from 'vue'; 
const breadcrumbs = [{title: 'Ventas', href:'/ventas'}];
const form = useForm({
    cliente_id: null,
    productos: [] as { producto_id: number; codigo_barras:string;
         nombre: string; cantidad: number; precio_unitario: number; total: number }[],
});
const props = defineProps<{
    clientes: Cliente[];
}>();
function handleSelect(opcion:any){ 
    console.log(opcion.id);
}
async function agregarProducto(evento:KeyboardEvent){
try{
    const input = (evento.target as HTMLInputElement);
    const codigo = input.value;
   const data = (await axios.get(`/productos/${codigo}`)).data;
    input.value = '';
    console.log(data);
    addRow(data);
}catch(err){
    console.log(err);
}
}
function addRow(data:any){
    form.productos.push({
        producto_id: data.producto_id,
        nombre: data.nombre,
        cantidad: 1,
        precio_unitario: data.precio,
        total: 0,
        codigo_barras:data.codigo_barras
    });
}
</script>
<template> 
<Head title="Ventas"/>
<AppLayout  :breadcrumbs="breadcrumbs">
<div class="container">
    <h2 class="text-center">Ventas</h2>
   <label for="">Codigo de barras <input type="text" @keypress="agregarProducto" class="border rounded" placeholder="Codigo de barrras" ></label>
    <SearchAutocomplete :datos="clientes" @select="handleSelect"/>
    <table class="min-w-full bg-black border border-gray-300 rounded-lg overflow-hidden">
        <thead>
            <th>Codigo de barras</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
            <th>Total</th>
            <th>Eliminar</th>
        </thead>
        <tbody>
            <tr v-for="producto in form.productos" :key="producto.producto_id" class="border-t text-center">
                <td>{{producto.codigo_barras}}</td>
                <td>{{ producto.nombre }}</td>
                <td><input type="number"  v-model.number="producto.cantidad"  class="text-center" min="1" ></td>
                <td><input type="number" v-model.number="producto.precio_unitario" step="0.01"  class="text-center" min="1" ></td>
                <td>${{ producto.cantidad * producto.precio_unitario }}</td>
                <td ><button class="p-1 mt-1 bg-red-500 rounded min-h-full cursor-pointer"><Trash2 /></button></td>
            </tr>
        </tbody>
    </table>
    </div>
</AppLayout>  </template>
