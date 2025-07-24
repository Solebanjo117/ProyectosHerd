<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed,defineProps } from 'vue';
import Graphics from './components/Graphics.vue';
const breadcrumbs = computed(() => [
  { title: 'Dashboard', href: '/dashboard' }
]);
const props = defineProps<{
  datos: { fecha: string; total: number }[]
}>();
const labels = props.datos.map(d => d.fecha);
const data = props.datos.map(d => d.total);
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <Graphics class="mt-10" chart-type="area" title="Gráfica de Ventas" :labels="labels" :data="data" />
  </AppLayout>
</template>
