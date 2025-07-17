<template>
    <div class="mt-4 ml-4 flex gap-2">
  <button
    v-for="link in propiedades.links"
    :key="link.label"
    :disabled="!link.url"
    v-html="link.label"
    @click="irAPagina(link.url)"
    :class="[
      'px-3 py-1 border rounded',
      link.active ? 'bg-blue-500 text-white' : 'bg-white text-gray-700',
      !link.url && 'opacity-50 cursor-not-allowed',
    ]"
  ></button>
</div>
</template>
<script setup lang="ts">
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Pagination } from '@/types';
const props = defineProps<{
  propiedades: Pagination<any>;
}>();
function irAPagina(url: string | null) {
  if (url) router.visit(url);
}
</script>