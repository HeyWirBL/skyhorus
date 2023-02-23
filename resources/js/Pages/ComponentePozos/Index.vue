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
  componentePozos: Object,
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
    router.get('/componente-pozos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.componentePozos.data) {
      selected.value.push(props.componentePozos.data[i].id)
    }
  }
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar estos componentes de pozo?',
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
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos componentes de pozo?',
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
</script>

<template>
  <div>
    <Head title="Componentes de Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Componentes de Pozos</h1>
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
      <Link v-if="can.createComponentePozo" class="btn-yellow mr-2" href="/componente-pozos/crear">
        <span>Importar</span>
        <span class="hidden md:inline">&nbsp;Excel</span>
      </Link>
      <button v-if="componentePozos.data.length !== 0" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th v-if="can.editComponentePozo" scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-componente-pozos" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-componente-pozos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">No.</th>
            <th scope="col" class="px-6 py-3">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3">Nombre del Componente</th>
            <th scope="col" class="px-6 py-3">Equipo Utilizado</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha de Recepción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="componentePozo in props.componentePozos.data" :key="componentePozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td v-if="can.editComponentePozo" class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-componentespozo-${componentePozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="componentePozo.id" />
                <label :for="`checkbox-componentespozo-${componentePozo.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`">
                {{ componentePozo.id }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                <div v-if="componentePozo.pozo">
                  {{ componentePozo.pozo.nombre_pozo }}
                </div>
                <Icon v-if="componentePozo.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" name="trash" />
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                {{ componentePozo.nombre_componente }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                {{ componentePozo.equipo_utilizado }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                {{ componentePozo.fecha_recep }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.componentePozos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron componentes de pozos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="props.componentePozos.links" :total="props.componentePozos.total" />
  </div>
</template>
