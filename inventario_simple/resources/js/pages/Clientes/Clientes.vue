
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { computed,defineProps  } from 'vue';
import type { Cliente,Pagination } from '@/types';
import { Head,router } from '@inertiajs/vue3';
import Tabla from './Tabla.vue';
import Form from './Form.vue';
import SearchInput from '../components/SearchInput.vue';
import Button from '@/components/ui/button/Button.vue';
import PaginationLinks from '../components/PaginationLinks.vue';
const props = defineProps<{
  clientes: Pagination<Cliente>;
  modo?: string; // Indica si estamos en modo edición o creación
  cliente?: Cliente; // Al editar un cliente, se pasa el cliente a editar
}>();
const breadcrumbs = computed(() => {
  const items = [
    { title: 'Clientes', href: '/clientes' }
  ];
  if (props.modo === 'create') {
    items.push({ title: 'Crear cliente', href: '/clientes/create' });
  } else if (props.modo === 'edit' && props.cliente) {
    items.push({ title: `Editar cliente ${props.cliente.nombre} #${props.cliente.id}`, href: '#' });
  } // Pendiente de implementar en el futuro

  return items;
});
</script>
<template>
    <Head title="Clientes"></Head>
    <div v-if="modo">
    <Head :title="modo === 'edit'? 'Edición de clientes' :'Creación de clientes'"></Head>
  </div>
    <AppLayout :breadcrumbs="breadcrumbs">
       <div class=" text-center mt-1">
      <h2 class="text-xl font-semibold">Lista de clientes</h2>
      <p class="text-white-600 mb-4">Aquí puedes ver y gestionar todos los clientes.</p>
      <SearchInput initialSearch="clientes" />
    </div>
    
    <div v-if="modo" class="flex justify-center my-4">
  <Form :cliente="cliente" />
</div>
      <Tabla :clientes="clientes.data"></Tabla>
      <Button class=" max-w-md mx-auto block hover:cursor-pointer"
      @click="router.get('/clientes/create', {}, { preserveScroll: false, preserveState: false })">
      Agregar Cliente
    </Button>
     <PaginationLinks :propiedades="clientes" />
    </AppLayout>
</template>