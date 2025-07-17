<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs = computed(() => [
  { title: 'Dashboard', href: '/dashboard' }
]);
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <h1 class="text-2xl font-bold">Bienvenido al dashboard</h1>
  </AppLayout>
</template>
