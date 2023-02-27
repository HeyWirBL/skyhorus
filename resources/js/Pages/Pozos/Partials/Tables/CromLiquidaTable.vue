<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  pozo: Object,
})

const cromatografiaLiquidas = computed(() => props.pozo.cromatografiaLiquidas)
</script>

<template>
  <div>
    <table class="w-full whitespace-nowrap">
      <thead class="text-sm text-left font-bold uppercase bg-white border-b">
        <tr>
          <th v-if="cromatografiaLiquidas.length !== 0" scope="col" class="p-4">
            <div class="flex items-center">
              <input id="checkbox-all-cromliquidas" v-model="selectAllCromLiquidas" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllCromLiquidas" />
              <label for="checkbox-all-cromliquidas" class="sr-only">checkbox</label>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">Archivo</th>
          <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cromliquida in cromatografiaLiquidas" :key="cromliquida.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
          <td class="w-4 p-4">
            <div class="flex items-center">
              <input :id="`checkbox-cromliquida-${cromliquida.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromliquida.id" @change="changeToggleAllCromLiquidas" />
              <label :for="`checkbox-cromliquida-${cromliquida.id}`" class="sr-only">checkbox</label>
            </div>
          </td>
          <td>
            <Link class="flex items-center px-6 py-4" :href="`/cromatografia-liquidas/${cromliquida.id}`" tabindex="-1">{{ cromliquida.documento }} </Link>
          </td>
          <td>
            <Link class="flex items-center px-6 py-4" :href="`/cromatografia-liquidas/${cromliquida.id}`" tabindex="-1">{{ cromliquida.fecha_hora }} </Link>
          </td>
          <td class="w-px">
            <Link class="flex items-center px-6" :href="`/cromatografia-liquidas/${cromliquida.id}`" tabindex="-1">
              <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
            </Link>
          </td>
        </tr>
        <tr v-if="cromatografiaLiquidas.length === 0">
          <td class="px-6 py-4" colspan="4">No se encontraron documentos registrados.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
