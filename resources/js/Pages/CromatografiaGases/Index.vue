<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  cromatografiaGases: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAll = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

const formCromatografiaGas = useForm({})
const isTrashed = computed(() => usePage().url.includes('trashed=only'))

watch(
  () => form.value,
  debounce(function () {
    router.get('/cromatografia-gases', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const filesize = (size) => {
  let i = Math.floor(Math.log(size) / Math.log(1024))
  return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i]
}

const toggleAll = () => {
  selected.value = []
  if (!selectAll.value) {
    selected.value = selected.value.length === props.cromatografiaGases.data.length ? [] : props.cromatografiaGases.data.map((cromatografiaGas) => cromatografiaGas.id)
  }
}

const changeToggleAll = () => {
  if (props.cromatografiaGases.data.length === selected.value.length) {
    selectAll.value = true
  } else {
    selectAll.value = false
  }
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este documento?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formCromatografiaGas.delete(`/cromatografia-gases/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAll.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos documentos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        //props.cromatografiaGases.data
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer este documento?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formCromatografiaGas.put(`/cromatografia-gases/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAll.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos documentos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        // TODO: restore every item selected
      }
    })
  }
}
</script>

<template>
  <div>
    <Head title="Cromatografía de Gas" />
    <h1 class="mb-8 text-3xl font-bold">Cromatografía de Gas</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <Link class="btn-yellow mr-2" href="/cromatografia-gases/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
      <button v-if="cromatografiaGases.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
      <button v-if="cromatografiaGases.data.length !== 0 && can.restoreCromatografiaGas && isTrashed" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-cromgas" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAll" />
                <label for="checkbox-all-cromgas" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Archivo</th>
            <th scope="col" class="px-6 py-3">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cromatografiaGas in cromatografiaGases.data" :key="cromatografiaGas.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-cromatografiagas-${cromatografiaGas.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaGas.id" @change="changeToggleAll" />
                <label :for="`checkbox-cromatografiagas-${cromatografiaGas.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="flex items-center px-6 py-4">
              <Link class="text-yellow-400 hover:underline focus:text-yellow-500 leading-snug" :href="`/cromatografia-gases/${cromatografiaGas.id}/editar`">
                {{ cromatografiaGas.documento[0].usrName }}
              </Link>
              <span class="text-xs ml-2 leading-snug">
                {{ filesize(cromatografiaGas.documento[0].size) }}
              </span>
              <Icon v-if="cromatografiaGas.deleted_at" class="ml-2 w-3 h-3 fill-yellow-400" name="trash" />
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/cromatografia-gases/${cromatografiaGas.id}/editar`">
                {{ cromatografiaGas.pozo.nombre_pozo }}
                <Icon v-if="cromatografiaGas.pozo.deleted_at" class="ml-2 w-3 h-3 fill-yellow-400" name="trash" />
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/cromatografia-gases/${cromatografiaGas.id}/editar`" tabindex="-1">{{ cromatografiaGas.fecha_hora }} </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/cromatografia-gases/${cromatografiaGas.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
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
