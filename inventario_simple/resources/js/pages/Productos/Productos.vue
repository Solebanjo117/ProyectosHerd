<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head,router } from '@inertiajs/vue3';
import { computed } from 'vue';
import Tabla from './Tabla.vue';
import PaginationLinks from '@/pages/components/PaginationLinks.vue';
import type { Producto, Pagination } from '@/types';
import SearchInput from '../components/SearchInput.vue';
import Button from '@/components/ui/button/Button.vue';
import Form from './Form.vue';
const breadcrumbs = computed(() => {
  const items = [
    { title: 'Productos', href: '/productos' }
  ];

  if (props.modo === 'create') {
    items.push({ title: 'Crear producto', href: '/productos/create' });
  } else if (props.modo === 'edit' && props.producto) {
    items.push({ title: `Editar producto ${props.producto.nombre} #${props.producto.id}`, href: '#' });
  }

  return items;
});
const props = defineProps<{
  productos: Pagination<Producto>;
  modo?: string; // Indica si estamos en modo edición
  // Si es true, se muestra el título de edición y se permite editar un producto
  producto?: Producto; //Al editar un producto, se pasa el producto a editar
}>();
</script>

<template>

  <Head title="Productos" />
  <div v-if="modo">
    <Head :title="modo === 'edit'? 'Edición de productos' :'Creación de productos'"></Head>
  </div>
    
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class=" text-center mt-1">
      <h2 class="text-xl font-semibold">Lista de productos</h2>
      <p class="text-white-600 mb-4">Aquí puedes ver y gestionar todos los productos de tu inventario.</p>
      <SearchInput initialSearch="productos" />
    </div>

<div v-if="modo" class="flex justify-center my-4">
  <Form :producto="producto" />
</div>

    <Tabla :productos="productos.data"></Tabla>
    <Button class=" max-w-md mx-auto block hover:cursor-pointer"
      @click="router.get('/productos/create', {}, { preserveScroll: false, preserveState: false })">
      Agregar Producto
    </Button>
    <PaginationLinks :propiedades="productos" />
  </AppLayout>



</template>
