<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  directorios: Object,
  filters: Object,
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
    router.get('/directorios', pickBy(form.value), { preserveState: true, replace: true })
  }, 150),
  {
    deep: true,
  },
)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.directorios.data) {
      selected.value.push(props.directorios.data[i].id)
    }
  }
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}
</script>

<template>
  <div>
    <Head title="Directorios" />
    <h1 class="mb-8 text-3xl font-bold">Carpetas</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow" href="/directorios/crear">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Directorio</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-directorios" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-directorios" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">&nbsp;</th>
            <th scope="col" class="px-6 py-3">Nombre de carpeta</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="dir in props.directorios.data" :key="dir.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-directorio-${dir.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="dir.id" />
                <label :for="`checkbox-directorio-${dir.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="flex items-center">
              <Link class="px-6 py-4 text-yellow-500 hover:underline focus:text-yellow-500" :href="`/directorios/${dir.id}`"> Archivos ({{ 0 }}) </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/directorios/${dir.id}/editar`" tabindex="-1">
                {{ dir.nombre_dir }}
                <Icon v-if="dir.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" name="trash" />
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/directorios/${dir.id}/editar`" tabindex="-1">
                {{ dir.fecha_dir }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/directorios/${dir.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.directorios.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron directorios registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="pt-4" :links="props.directorios.links" :total="props.directorios.total" />
  </div>
</template>
