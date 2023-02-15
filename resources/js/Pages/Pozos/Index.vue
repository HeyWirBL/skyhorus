<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  filters: Object,
  pozos: Object,
})
</script>

<template>
  <div>
    <Head title="Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Pozos</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter class="mr-4 w-full max-w-md">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <div>
        <Link class="btn-yellow mr-4" href="/pozos/crear">
          <span>Crear</span>
          <span class="hidden md:inline">&nbsp;Pozo</span>
        </Link>
        <button type="button">
          <Icon class="w-6 h-6" name="three-dots" />
        </button>
      </div>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left fond-bold">
            <th class="pb-4 pt-6 px-6">Punto Muestreo</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pozo in pozos.data" :key="pozo.idPozo" class="hover:bg-gray-100 focus-within:bg-gray-100">
            weef
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
