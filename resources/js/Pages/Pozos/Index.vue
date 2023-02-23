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
  pozos: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAll = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

const formPozo = useForm({})
const isTrashed = computed(() => usePage().url.includes('trashed=only'))

watch(
  () => form.value,
  debounce(function () {
    router.get('/pozos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const toggleAll = () => {
  selected.value = []
  if (!selectAll.value) {
    selected.value = selected.value.length === props.pozos.data.length ? [] : props.pozos.data.map((pozo) => pozo.id)
  }
}

const changeToggleAll = () => {
  if (props.pozos.data.length === selected.value.length) {
    selectAll.value = true
  } else {
    selectAll.value = false
  }
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formPozo.delete(`/pozos/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAll.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos pozos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formPozo.post('/pozos/destroy-all', {
          data: {
            ids: selected.value,
          },
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAll.value = false),
        })
        selected.value = props.pozos.data.filter((pozo) => !selected.value.includes(pozo.id))
        selected.value = []
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer este pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        formPozo.put(`/pozos/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAll.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos pozos?',
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
    <Head title="Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Pozos</h1>
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
      <Link v-if="can.createPozo" class="btn-yellow mr-2" href="/pozos/crear">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Pozo</span>
      </Link>
      <button v-if="pozos.data.length !== 0 && can.deletePozo && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="removeSelectedItems">
        <span>Borrar Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
      <button v-if="pozos.data.length !== 0 && can.restorePozo && isTrashed" class="btn-secondary" type="button" :disabled="!selectAll && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer Elementos</span>
        <span class="hidden md:inline">&nbsp;Seleccionados</span>
      </button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th v-if="can.editPozo" scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-pozos" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAll" />
                <label for="checkbox-all-pozos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">No.</th>
            <th scope="col" class="px-6 py-3">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3">Identificador</th>
            <th scope="col" class="px-6 py-3" colspan="2">Fecha del Muestreo</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pozo in pozos.data" :key="pozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td v-if="can.editPozo" class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-pozo-${pozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="pozo.id" @change="changeToggleAll" />
                <label :for="`checkbox-pozo-${pozo.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/pozos/${pozo.id}`">
                {{ pozo.id }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/pozos/${pozo.id}`" tabindex="-1">
                {{ pozo.nombre_pozo }}
                <Icon v-if="pozo.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/pozos/${pozo.id}`" tabindex="-1">
                {{ pozo.identificador === '' ? 'No hay identificador' : pozo.identificador }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/pozos/${pozo.id}`" tabindex="-1">
                {{ pozo.fecha_hora }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/pozos/${pozo.id}`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="pozos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron pozos {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="pozos.links" :total="pozos.total" />
  </div>
</template>
