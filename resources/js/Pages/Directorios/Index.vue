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
const isSelected = ref(false)

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
      selected.value.push(props.directorios.data[i].idDirectorio)
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
        <span>Añadir</span>
        <span class="hidden md:inline">&nbsp;Directorio</span>
      </Link>
    </div>
    <div v-if="selectAll || isSelected" class="flex mb-6">
      <button class="text-red-600 hover:underline" type="button">Borrar elementos seleccionados</button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-users" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-users" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">&nbsp;</th>
            <th scope="col" class="px-6 py-3">Nombre de carpeta</th>
            <th scope="col" colspan="2" class="px-6 py-3">Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="directorio in props.directorios.data" :key="directorio.idDirectorio" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-user-${directorio.idDirectorio}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="directorio.idDirectorio" />
                <label :for="`checkbox-user-${directorio.idDirectorio}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="text-yellow-400 hover:underline px-6 py-4" href="#" tabindex="-1">Archivos</Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/directorios/${directorio.idDirectorio}/editar`" tabindex="-1">
                {{ directorio.nombre_dir }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/directorios/${directorio.idDirectorio}/editar`" tabindex="-1">
                {{ directorio.fecha_dir }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/directorios/${directorio.idDirectorio}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.directorios.data.length === 0">
            <td class="px-6 py-4">No se encontraron carpetas registradas.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="pt-4" :links="props.directorios.links" :total="props.directorios.total" />
  </div>
</template>
