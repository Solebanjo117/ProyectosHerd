<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head,router } from '@inertiajs/vue3';
import { defineProps,computed } from 'vue';
import Form from './Form.vue';
import PaginationLinks from '../components/PaginationLinks.vue';
import AutoTable from '../components/AutoTable.vue';
import Button from '@/components/ui/button/Button.vue';
import type {Salida,Pagination, Cliente} from '@/types'; // Asegúrate de que la ruta sea correcta
const props = defineProps<{
    salidas: Pagination<Salida>; // Define el tipo de Salida según tu modelo
    modo?: string; // Indica si estamos en modo edición o creación
    salida?: Salida; // Al editar una salida, se pasa la salida a editar
    clientes?:Cliente[]; // Lista de clientes para el formulario
}>();

const breadcrumbs = computed(() => {
  const items = [
    { title: 'Salidas', href: '/salidas' }
  ];
  if (props.modo === 'create') {
    items.push({ title: 'Crear salida', href: '/salidas/create' });
  } else if (props.modo === 'edit' && props.salida) {
    items.push({ title: `Editar salida #${props.salida.id}`, href: '#' });
  }

  return items;
});
const columnas= [
    { key: 'id', label: 'ID' },
    { key: 'tipo', label: 'Tipo' },
    { key: `cliente.nombre`, label: 'Cliente' },
    { key: 'estado', label: 'Estado' },
    { key: 'observaciones', label: 'Observaciones' },
    { key: 'updated_at_formatted', label: 'Ultima vez' },
];
</script>
<template>
    <Head title="Salidas"></Head>
    <Head v-if="modo" :title="modo === 'edit'? 'Edición de salida' :'Creación de salida'"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Salidas</h1>
        <p>Esta página está en construcción.</p>
        </div>
        <div v-if="modo" class="flex justify-center my-4">
            <Form  :salida="salida" :clientes="clientes"></Form>
        </div>
        <div class="container mx-auto p-4">
            <AutoTable :data="props.salidas.data" :columns="columnas" route="salidas"/>
        </div>
        <Button class=" max-w-md mx-auto block hover:cursor-pointer" 
        @click="router.get('/salidas/create', {}, { preserveScroll: false, preserveState: false })">Agregar salida</Button>
        <PaginationLinks :propiedades="salidas"/>
    </AppLayout>
</template>