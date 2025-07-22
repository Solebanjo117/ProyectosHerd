<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps,computed } from 'vue';
import { Entrada,Pagination } from '@/types';
import Form from './Form.vue';
import AutoTable from '../components/AutoTable.vue';
import { router } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import PaginationLinks from '../components/PaginationLinks.vue';
const props = defineProps<{
  entradas: Pagination<Entrada>;
  modo?: string; // Indica si estamos en modo edición o creación
  entrada?: Entrada; // Al editar un cliente, se pasa el cliente a editar
}>();
const breadcrumbs = computed(() => {
  const items = [
    { title: 'Entradas', href: '/entradas' }
  ];
  if (props.modo === 'create') {
    items.push({ title: 'Crear entrada', href: '/entradas/create' });
  } else if (props.modo === 'edit') {
    items.push({ title: 'Editar entrada', href: '#' });
  } // Pendiente de implementar en el futuro

  return items;
});

const columnas= [
    { key: 'id', label: 'ID' },
    { key: 'referencia', label: 'Referencia' },
    { key: 'tipo', label: 'Tipo' },
    { key: 'observaciones', label: 'Observaciones' },
    { key: 'updated_at_formatted', label: 'Fecha' },
];
</script>
<template>
    <Head title="Entradas"></Head>
    <Head v-if="modo" :title="modo === 'edit'? 'Edición de entrada' :'Creación de entrada'"></Head>
  
<AppLayout :breadcrumbs="breadcrumbs">
     <div class=" text-center mt-1">
        <h2 class="text-xl font-semibold">Lista de entradas</h2>
        <p class="text-white-600 mb-4">Aquí puedes ver y gestionar todas las entradas.</p>
        </div>
    <div v-if="modo" class="flex justify-center my-4">
  <Form :entrada="entrada" />
</div>     
<div class="container mx-auto p-4 ">

<AutoTable :data="entradas.data" :columns="columnas" route="entradas" />
</div>
<Button class=" max-w-md mx-auto block hover:cursor-pointer"
      @click="router.get('/entradas/create', {}, { preserveScroll: false, preserveState: false })">
      Agregar Entrada
    </Button>
<PaginationLinks :propiedades="entradas"></PaginationLinks>
</AppLayout>
</template>