<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  directorios: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAllDir = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

const formDirectorio = useForm({})

const isTrashed = computed(() => usePage().url.includes('trashed=only'))

/**
 * Helper Function that that checks whether the `selectAllRef` flag is set
 * to false.
 *
 * @param {array} items an array of items.
 * @param {array} selectedItems an array of selected items.
 * @param {bool} selectAllRef boolean flag that represents whether all items are selected.
 */
const toggleAll = (items, selectedItems, selectAllRef) => {
  selectedItems.value = []
  if (!selectAllRef.value) {
    selectedItems.value = selectedItems.value.length === items.length ? [] : items.map((item) => item.id)
  }
}

/**
 * Helper Function that updates the state of the "select all" checkbox
 * when individual checkboxes are checked or unchecked.
 *
 * @param {array} items list of items that can be selected.
 * @param {array} selectedItems an array which contains the ids of the items that have been selected.
 * @param {bool} selectAllRef reference that represents the state of the "select all" checkbox.
 */
const changeToggleAll = (items, selectedItems, selectAllRef) => {
  if (items.length === selectedItems.value.length) {
    selectAllRef.value = true
  } else {
    selectAllRef.value = false
  }
}

const toggleAllDir = () => {
  toggleAll(props.directorios.data, selected, selectAllDir)
}

const changeToggleAllDir = () => {
  changeToggleAll(props.directorios.data, selected, selectAllDir)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este directorio?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formDirectorio.delete(`/directorios/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos directorios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formDirectorio.delete(`/directorios?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer este directorio?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formDirectorio.put(`/directorios/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos directorios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formDirectorio.put(`/directorios?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/directorios', pickBy(form.value), { preserveState: true, replace: true })
  }, 150),
  {
    deep: true,
  },
)
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
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link v-if="can.createDirectorio" class="btn-yellow" href="/directorios/crear">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Directorio</span>
      </Link>
    </div>
    <div class="flex items-center mb-6">
      <button v-if="directorios.data.length !== 0 && can.deleteDirectorio && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllDir && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
      <button v-if="directorios.data.length !== 0 && can.restoreDirectorio && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllDir && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="directorios.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="directorios.data.length !== 0 && can.editDirectorio" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-directorios" v-model="selectAllDir" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllDir" />
                <label for="checkbox-all-directorios" class="sr-only">checkbox</label>
              </div>
            </th>
            <th v-if="directorios.data.length !== 0" scope="col" class="px-6 py-3 border-solid border border-gray-200" />
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Nombre de carpeta</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="dir in directorios.data" :key="dir.id" class="bg-white border-b">
            <td v-if="can.editDirectorio" class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap">
                <Link class="flex items-center" :href="`/directorios/${dir.id}/editar`" tabindex="-1">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </Link>
              </span>
            </td>
            <td v-if="can.editDirectorio" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-directorio-${dir.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="dir.id" @change="changeToggleAllDir" />
                <label :for="`checkbox-directorio-${dir.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">Archivos ({{ dir.documentos.length }})</span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ dir.nombre_dir }}</span>
                <span v-if="dir.deleted_at" title="Esta carpeta ha sido eliminada.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span>{{ dir.fecha_dir }}</span>
            </td>
          </tr>
          <tr v-if="directorios.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron carpetas {{ form.trashed === 'only' ? 'eliminadas' : 'registradas' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="pt-4" :links="directorios.links" :total="directorios.total" />
  </div>
</template>
