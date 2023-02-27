<script setup>
import { computed } from 'vue'

const props = defineProps({
  pozo: Object,
})

const docPozos = computed(() => props.pozo.docPozos)
</script>

<template>
  <div>
    <table class="w-full whitespace-nowrap">
      <thead class="text-sm text-left font-bold uppercase bg-white border-b">
        <tr>
          <th v-if="pozo.docPozos.length !== 0" scope="col" class="p-4">
            <div class="flex items-center">
              <input id="checkbox-all-docpozos" v-model="selectAll" class="w-4 h-4 text-blue bg-gray-100 border-gray-300 rounded focus:ring-blue-500" type="checkbox" @click="toggleAllDocPozos" />
              <label for="checkbox-all-docpozos" class="sr-only">checkbox docpozo</label>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">Documento</th>
          <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="docpozo in docPozos" :key="docpozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
          <td class="w-4 p-4">
            <div class="flex items-center">
              <input :id="`checkbox-docpozo-${docpozo.id}`" v-model="selected" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" type="checkbox" @change="changeToggleAllDocPozos" />
              <label :for="`checkbox-docpozo-${docpozo.id}`" class="sr-only">checkbox docpozo</label>
            </div>
          </td>
        </tr>
        <tr v-if="docPozos.length === 0">
          <td class="px-6 py-4" colspan="4">No se encontraron documentos de pozo registrados.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
