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
        <form @submit.prevent="guardar">
        <div class="container">
            <h2 class="text-center">Ventas</h2>
            <label for="">Codigo de barras <input type="text" @keypress="agregarProducto" class="border rounded"
                    placeholder="Codigo de barrras"></label>
            <SearchAutocomplete :datos="clientes" @select="handleSelect" />
            <div class="justify-center flex">
                <table class=" min-w-full bg-black border border-gray-500 rounded-lg overflow-hidden">
                    <thead>
                        <th>Codigo de barras</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Total</th>
                        <th>Eliminar</th>
                    </thead>
                    <tbody>
                        <tr v-for="producto, index in form.productos" :key="producto.producto_id"
                            class="border-t text-center">
                            <td>{{ producto.codigo_barras }}</td>
                            <td>{{ producto.nombre }}</td>
                            <td><input type="number" v-model.number="producto.cantidad" class="text-center" min="1">
                            </td>
                            <td><input type="number" v-model.number="producto.precio_unitario" step="0.01"
                                    class="text-center" min="1"></td>
                            <td>${{ (producto.cantidad * producto.precio_unitario).toFixed(2) }}</td>
                            <td><button @click="form.productos.splice(index, 1)"
                                    class="p-1 mt-1 bg-red-500 rounded min-h-full cursor-pointer">
                                    <Trash2 />
                                </button></td>
                        </tr>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total</td>
                            <td>${{ total.toFixed(2) }}</td>
                        </tr>
                        <tr class="bg-gray-800 text-center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Dinero recibido</td>
                            <td><input class="text-center bg-gray-500" v-model="recibido" type="number" min="1"></td>
                            <td></td>
                        </tr>
                        <tr class="bg-gray-800 text-center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Cambio</td>
                            <td>${{ recibido - parseInt(total.toFixed(2)) }}</td>
                            <td></td>
                        </tr>

                    </tfoot>
                </table>
            </div>
            <div class="flex justify-center mt-10"> <Button class="cursor-pointer" type="submit">Hacer venta</Button></div>
        </div>
        </form>
    </AppLayout>
</template>
