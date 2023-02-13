<script setup>
import Dropdown from '@/Components/Dropdown.vue'

defineEmits(['update:modelValue', 'reset'])

const props = defineProps({
  modelValue: String,
  maxWidth: {
    type: Number,
    default: 300,
  },
})
</script>

<template>
  <div class="flex items-center">
    <div class="flex w-full bg-white rounded-md shadow-sm">
      <Dropdown :auto-close="false" class="focus:z-10 px-4 hover:bg-gray-100 border border-gray-300 focus:border-blue-300 rounded-l focus:ring focus:ring-blue-200 focus:ring-opacity-50 md:px-6" placement="bottom-start">
        <template #default>
          <div class="flex items-baseline">
            <span class="hidden text-gray-700 md:inline">Filtrar:</span>
            <svg class="w-2 h-2 fill-gray-700 md:ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 961.243 599.998">
              <path d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z" />
            </svg>
          </div>
        </template>
        <template #dropdown>
          <div class="mt-2 px-4 py-6 w-screen bg-white rounded shadow-xl" :style="{ maxWidth: `${props.maxWidth}px` }">
            <slot />
          </div>
        </template>
      </Dropdown>
      <label for="table-search-users" class="sr-only">search</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg class="w-5 h-5 fill-gray-700" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
        </div>
        <input id="table-search-users" type="text" :value="props.modelValue" class="block py-3 pl-10 w-full md:w-80 rounded-r border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" autocomplete="off" name="search" placeholder="Buscar" @input="$emit('update:modelValue', $event.target.value)" />
      </div>
    </div>
    <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-yellow-500 text-sm" type="button" @click="$emit('reset')">Reiniciar</button>
  </div>
</template>
