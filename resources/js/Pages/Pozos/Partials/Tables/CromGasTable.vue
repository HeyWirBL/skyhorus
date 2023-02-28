<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  filters: Object,
  pozo: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAllCromGas = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

const cromatografiaGasForm = useForm({})

const isTrashed = computed(() => usePage().url.includes('trashed=only'))
const cromatografiaGases = computed(() => props.pozo.cromatografiaGases)

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

const toggleAllCromGas = () => {
  toggleAll(cromatografiaGases.value.data, selected, selectAllCromGas)
}
const changeToggleAllCromGas = () => {
  changeToggleAll(cromatografiaGases.value.data, selected, selectAllCromGas)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const filesize = (size) => {
  let i = Math.floor(Math.log(size) / Math.log(1024))
  return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i]
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este documento?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        cromatografiaGasForm.delete(`/cromatografia-gases/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromGas.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos documentos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        cromatografiaGasForm.delete(`/cromatografia-gases?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromGas.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get(`/pozos/${props.pozo.id}`, pickBy(form.value), { preserveScroll: true, preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <h2 class="mb-8 text-2xl font-bold">Cromatografía de Gas</h2>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <a class="btn-yellow" href="#">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </a>
    </div>
    <div class="flex items-center mb-6">
      <button v-if="cromatografiaGases.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllCromGas && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="cromatografiaGases.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="cromatografiaGases.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-cromgas" v-model="selectAllCromGas" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllCromGas" />
                <label for="checkbox-all-cromgas" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Archivo</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cromatografiaGas in cromatografiaGases.data" :key="cromatografiaGas.id" class="bg-white border-b">
            <td class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap">
                <Link class="flex items-center" :href="`/cromatografia-gases/${cromatografiaGas.id}/editar`" tabindex="-1">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </Link>
              </span>
            </td>
            <td class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-cromgas-${cromatografiaGas.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaGas.id" @change="changeToggleAllCromGas" />
                <label :for="`checkbox-cromgas-${cromatografiaGas.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center leading-snug">
                <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/cromatografia-gases/${cromatografiaGas.documento}/descargar`">
                  {{ cromatografiaGas.documento }}
                </a>
                <span class="text-xs ml-2"> size </span>
                <span v-if="cromatografiaGas.deleted_at" title="Este documento ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ pozo.nombre_pozo }}</span>
                <span v-if="pozo.deleted_at" title="Este pozo ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ cromatografiaGas.fecha_hora }}</span>
            </td>
          </tr>
          <tr v-if="cromatografiaGases.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="cromatografiaGases.links" :total="cromatografiaGases.total" />
  </div>
</template>
