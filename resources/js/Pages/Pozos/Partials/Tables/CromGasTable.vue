<script setup>
import { computed, inject, ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  pozo: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAllCromGas = ref(false)

const cromatografiaGasForm = useForm({})

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

/**
 * Converts a file size in bytes to the nearest unit of measurement (B, kB, MB, GB, etc.).
 * @param {number} size - The file size in bytes.
 * @param {number} [precision=2] - The number of decimal places to include in the output. Default is 2.
 * @returns {string} The formatted file size with the appropriate unit of measurement.
 */
const filesize = (size, precision = 2) => {
  const units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB']
  if (size < 0) {
    return 'Tipo de archivo inválido'
  } else if (size === 0) {
    return `0 ${units[0]}`
  } else if (size < 1) {
    return `${(size * 1024).toFixed(precision)} ${units[1]}`
  } else if (size >= Math.pow(1024, units.length - 1)) {
    return `${(size / Math.pow(1024, units.length - 1)).toFixed(precision)} ${units[units.length - 1]}`
  } else {
    const i = Math.floor(Math.log(size) / Math.log(1024))
    const val = (size / Math.pow(1024, i)).toFixed(precision)
    return `${val} ${units[i]}`
  }
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
</script>

<template>
  <div>
    <h2 class="mb-8 text-2xl font-bold">Cromatografía de Gas</h2>
    <div class="flex items-center mb-6">
      <button class="btn-yellow mr-2" type="button">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </button>
      <button v-if="cromatografiaGases.data.length !== 0" class="btn-secondary" type="button" :disabled="!selectAllCromGas && !selected.length" @click="removeSelectedItems">
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
                  {{ cromatografiaGas.documento.usrName }}
                </a>
                <span class="text-xs ml-2"> {{ filesize(cromatografiaGas.documento.size) }} </span>
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
            <td class="px-6 py-4" colspan="5">No se encontraron documentos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="cromatografiaGases.links" :total="cromatografiaGases.total" />
  </div>
</template>
