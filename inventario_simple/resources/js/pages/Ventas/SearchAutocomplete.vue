<template>
  <div class="relative w-64">
    <input
      v-model="search"
      type="text"
      class="w-full border rounded p-2"
      placeholder="Buscar..."
      @focus="showDropdown = true"
      @blur="handleBlur"
    />

    <ul
      v-if="showDropdown && filteredOptions.length"
      class="absolute z-10 bg-black border w-full mt-1 rounded shadow"
    >
      <li
        v-for="option in filteredOptions"
        :key="option.id"
        class="px-4 py-2 hover:bg-gray-200 cursor-pointer"
        @mousedown.prevent="selectOption(option)"
      >
        {{ option.nombre }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, computed,defineProps } from 'vue';
import type { Cliente } from '@/types';
// Lista de opciones (puedes pasarla como prop si lo prefieres)
const emit = defineEmits(['select']);
const props = defineProps<{
    datos: Cliente[]
}>();

const search = ref('')
const showDropdown = ref(false)

const filteredOptions = computed(() => {
  
  return props.datos.filter(option =>
    option.nombre.toLowerCase().includes(search.value.toLowerCase())
  )
})

const selectOption = (option:any) => {
  search.value = option.nombre
  showDropdown.value = false
  emit('select', option)
}

const handleBlur = () => {
  // Retardo necesario para que se dispare el evento `@mousedown` antes del blur
  setTimeout(() => {
    showDropdown.value = false
  }, 100)
}
</script>

<style scoped>
/* Opcionalmente puedes ajustar el diseño aquí */
</style>
