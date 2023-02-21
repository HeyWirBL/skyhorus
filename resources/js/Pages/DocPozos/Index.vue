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
  can: Object,
  filters: Object,
  docPozos: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAll = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  () => form.value,
  debounce(function () {
    router.get('/doc-pozos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.docPozos.data) {
      selected.value.push(props.docPozos.data[i].id)
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
    <Head title="Documentos de Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Documentos de Pozos</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <Link v-if="can.createDocPozo" class="btn-yellow mr-2" href="/doc-pozos/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
      <button v-if="docPozos.data.length !== 0" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th v-if="can.editDocPozo" scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-doc-pozos" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-doc-pozos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3">Documento</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="docPozo in docPozos.data" :key="docPozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td v-if="can.editDocPozo" class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-docpozo-${docPozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="docPozo.id" />
                <label :for="`checkbox-docpozo-${docPozo.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/doc-pozos/${docPozo.id}/editar`">
                {{ docPozo.pozo.nombre_pozo }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/doc-pozos/${docPozo.id}/editar`">
                {{ docPozo.documento }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/doc-pozos/${docPozo.id}/editar`" tabindex="-1">{{ docPozo.fecha_hora }} </Link>
            </td>
            <td v-if="can.editDocPozo" class="w-px">
              <Link class="flex items-center px-6" :href="`/doc-pozos/${docPozo.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="docPozos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="docPozos.links" :total="docPozos.total" />
  </div>
</template>
