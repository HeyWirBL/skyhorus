<script setup>
import { inject, ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  filters: Object,
  documentos: Object,
  anos: Array,
  meses: Array,
})

const swal = inject('$swal')

const selected = ref([])
const selectAll = ref(false)

const form = ref({
  search: props.filters.search,
  year: props.filters.year,
  month: props.filters.month,
  trashed: props.filters.trashed,
})

watch(
  () => form.value,
  debounce(function () {
    router.get('/documentos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const filesize = (size) => {
  let i = Math.floor(Math.log(size) / Math.log(1024))
  return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'][i]
}

const select = () => {
  selected.value = []

  if (!selectAll.value) {
    for (let i in props.documentos.data) {
      selected.value.push(props.documentos.data[i].id)
    }
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
        //props.documentos.data
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
        //props.documentos.data
      }
    })
  }
}
</script>

<template>
  <div>
    <Head title="Documentos" />
    <h1 class="mb-8 text-3xl font-bold">Documentos</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Año:</label>
        <select v-model="form.year" class="form-select mt-1 w-full">
          <option :value="null" />
          <option v-for="ano in anos" :key="ano.id" :value="ano.id">{{ ano.ano }}</option>
        </select>
        <label class="block mt-4 text-gray-700">Mes:</label>
        <select v-model="form.month" class="form-select mt-1 w-full">
          <option :value="null" />
          <option v-for="mes in meses" :key="mes.id" :value="mes.id">{{ mes.nombre }}</option>
        </select>
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <Link class="btn-yellow mr-2" href="/documentos/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
      <button v-if="documentos.data.length !== 0" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th v-if="documentos.data.length !== 0" scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-documentos" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-documentos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Documento</th>
            <th scope="col" class="px-6 py-3">Carpeta</th>
            <th scope="col" class="px-6 py-3">Año</th>
            <th scope="col" class="px-6 py-3" colspan="2">Mes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="documento in documentos.data" :key="documento.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-documento-${documento.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="documento.id" />
                <label :for="`checkbox-documento-${documento.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="flex items-center px-6 py-4">
              <div class="leading-snug">
                <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/documentos/${documento.documento.name}/descargar`">
                  {{ documento.documento.usrName }}
                </a>
                <span class="text-xs ml-2">
                  {{ filesize(documento.documento.size) }}  
                </span>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/documentos/${documento.id}`" tabindex="-1">
                {{ documento.directorio.nombre_dir }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/documentos/${documento.id}`" tabindex="-1">
                {{ documento.ano.ano }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/documentos/${documento.id}`" tabindex="-1">{{ documento.mes.nombre }} </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/documentos/${documento.id}`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="documentos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="documentos.links" :total="documentos.total" />
  </div>
</template>
