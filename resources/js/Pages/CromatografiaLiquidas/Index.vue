<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  filters: Object,
  cromatografiaLiquidas: Object,
})

const selected = ref([])
const selectAll = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  () => form.value,
  debounce(function () {
    router.get('/cromatografia-liquidas', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.cromatografiaLiquidas.data) {
      selected.value.push(props.cromatografiaLiquidas.data[i].id)
    }
  }
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}
</script>

<template>
  <div>
    <Head title="Cromatografía Líquida" />
    <h1 class="mb-8 text-3xl font-bold">Cromatografías Líquidas</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow mr-4" href="/cromatografia-liquidas/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-cromliquidas" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-cromliquidas" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Archivo</th>
            <th scope="col" class="px-6 py-3">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cromatografiaLiquida in props.cromatografiaLiquidas.data" :key="cromatografiaLiquida.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-cromatografiaLiquida-${cromatografiaLiquida.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaLiquida.id" />
                <label :for="`checkbox-cromatografiaLiquida-${cromatografiaLiquida.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/editar`">
                {{ cromatografiaLiquida.documento }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/editar`">
                {{ cromatografiaLiquida.pozo.nombre_pozo }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/editar`" tabindex="-1">{{ cromatografiaLiquida.fecha_hora }} </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.cromatografiaLiquidas.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron cromatografias líquidas registradas.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="props.cromatografiaLiquidas.links" :total="props.cromatografiaLiquidas.total" />
  </div>
</template>
