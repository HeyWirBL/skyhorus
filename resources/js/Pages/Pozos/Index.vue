<script setup>
import { computed, inject, nextTick, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  pozos: Object,
})

const swal = inject('$swal')

const createNewPozo = ref(false)
const editPozo = ref(false)

const selected = ref([])
const selectAllPozos = ref(false)
const createInputRef = ref(null)
const editInputRef = ref(null)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

/* Emtpy Form for deleting */
const pozoForm = useForm({})

/* Form for creating a new Pozo */
const createPozoForm = useForm({
  punto_muestreo: '',
  fecha_hora: '',
  identificador: '',
  presion_kgcm2: '',
  presion_psi: '',
  temp_C: '',
  temp_F: '',
  volumen_cm3: '',
  volumen_lts: '',
  observaciones: null,
  nombre_pozo: '',
})

/* Form for editing a Pozo */
const editPozoForm = useForm({
  _method: 'put',
  id: '',
  punto_muestreo: '',
  fecha_hora: '',
  identificador: '',
  presion_kgcm2: '',
  presion_psi: '',
  temp_C: '',
  temp_F: '',
  volumen_cm3: '',
  volumen_lts: '',
  observaciones: '',
  nombre_pozo: '',
})

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

const toggleAllPozos = () => {
  toggleAll(props.pozos.data, selected, selectAllPozos)
}
const changeToggleAllPozos = () => {
  changeToggleAll(props.pozos.data, selected, selectAllPozos)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const openModalCreateForm = () => {
  createNewPozo.value = true

  nextTick(() => createInputRef.value.focus())
}

const openModalEditForm = (pozo) => {
  // Set form field values
  editPozoForm.id = pozo.id
  editPozoForm.punto_muestreo = pozo.punto_muestreo
  editPozoForm.fecha_hora = pozo.fecha_hora
  editPozoForm.identificador = pozo.identificador
  editPozoForm.presion_kgcm2 = pozo.presion_kgcm2
  editPozoForm.presion_psi = pozo.presion_psi
  editPozoForm.temp_C = pozo.temp_C
  editPozoForm.temp_F = pozo.temp_F
  editPozoForm.volumen_cm3 = pozo.volumen_cm3
  editPozoForm.volumen_lts = pozo.volumen_lts
  editPozoForm.observaciones = pozo.observaciones
  editPozoForm.nombre_pozo = pozo.nombre_pozo

  editPozo.value = true

  nextTick(() => editInputRef.value.focus())
}

const closeModalCreateForm = () => {
  createNewPozo.value = false
  createPozoForm.reset()
}

const closeModalEditForm = () => {
  editPozo.value = false
  editPozoForm.reset()
}

const store = () => {
  createPozoForm.post('/pozos', {
    preserveScroll: true,
    onSuccess: () => closeModalCreateForm(),
    onError: () => createInputRef.value.focus(),
  })
}

const update = () => {
  editPozoForm.post(`/pozos/${editPozoForm.id}`, {
    preserveScroll: true,
    onSuccess: () => closeModalEditForm(),
    onFinish: () => {
      if (!editPozoForm.hasErrors) {
        editPozoForm.reset()
      }
    },
  })
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        pozoForm.delete(`/pozos/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllPozos.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos pozos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        pozoForm.delete(`/pozos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllPozos.value = false),
        })
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
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        pozoForm.put(`/pozos/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllPozos.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos pozos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        pozoForm.put(`/pozos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllPozos.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/pozos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Pozos</h1>
    <div class="flex items-center mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <button v-if="can.createPozo" class="btn-yellow mr-2" type="button" @click="openModalCreateForm">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Pozo</span>
      </button>
      <button v-if="pozos.data.length !== 0 && can.deletePozo && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllPozos && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="pozos.data.length !== 0 && can.restorePozo && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllPozos && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Create Pozo Form Modal -->
    <Modal :show="createNewPozo" style="max-width: 800px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Crear Pozo</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalCreateForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="store">
        <!-- Inputs -->
        <div class="flex flex-wrap -mb-8 -mr-6 p-4">
          <TextInput ref="createInputRef" v-model="createPozoForm.nombre_pozo" :error="createPozoForm.errors.nombre_pozo" class="pb-4 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
          <TextInput v-model="createPozoForm.temp_C" :error="createPozoForm.errors.temp_C" class="pb-4 pr-6 w-full lg:w-1/2" label="°C" />
          <TextInput v-model="createPozoForm.punto_muestreo" :error="createPozoForm.errors.punto_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
          <TextInput v-model="createPozoForm.temp_F" :error="createPozoForm.errors.temp_F" class="pb-4 pr-6 w-full lg:w-1/2" label="°F" />
          <TextInput v-model="createPozoForm.fecha_hora" :error="createPozoForm.errors.fecha_hora" class="pb-4 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
          <TextInput v-model="createPozoForm.volumen_cm3" :error="createPozoForm.errors.volumen_cm3" class="pb-4 pr-6 w-full lg:w-1/2" label="CM3" />
          <TextInput v-model="createPozoForm.identificador" :error="createPozoForm.errors.identificador" class="pb-4 pr-6 w-full lg:w-1/2" label="Identificador" />
          <TextInput v-model="createPozoForm.volumen_lts" :error="createPozoForm.errors.volumen_lts" class="pb-4 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
          <TextInput v-model="createPozoForm.presion_psi" :error="createPozoForm.errors.presion_psi" class="pb-4 pr-6 w-full lg:w-1/2" label="PSIA" />
          <TextInput v-model="createPozoForm.presion_kgcm2" :error="createPozoForm.errors.presion_kgcm2" class="pb-4 pr-6 w-full lg:w-1/2" label="KG/CM2" />
          <TextareaInput v-model="createPozoForm.observaciones" :error="createPozoForm.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="createPozoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalCreateForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Edit Pozo Form Modal -->
    <Modal :show="editPozo" style="max-width: 800px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Pozo [{{ editPozoForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <!-- Inputs -->
        <div class="flex flex-wrap -mb-8 -mr-6 p-4">
          <TextInput ref="editInputRef" v-model="editPozoForm.nombre_pozo" :error="editPozoForm.errors.nombre_pozo" class="pb-4 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
          <TextInput v-model="editPozoForm.temp_C" :error="editPozoForm.errors.temp_C" class="pb-4 pr-6 w-full lg:w-1/2" label="°C" />
          <TextInput v-model="editPozoForm.punto_muestreo" :error="editPozoForm.errors.punto_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
          <TextInput v-model="editPozoForm.temp_F" :error="editPozoForm.errors.temp_F" class="pb-4 pr-6 w-full lg:w-1/2" label="°F" />
          <TextInput v-model="editPozoForm.fecha_hora" :error="editPozoForm.errors.fecha_hora" class="pb-4 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
          <TextInput v-model="editPozoForm.volumen_cm3" :error="editPozoForm.errors.volumen_cm3" class="pb-4 pr-6 w-full lg:w-1/2" label="CM3" />
          <TextInput v-model="editPozoForm.identificador" :error="editPozoForm.errors.identificador" class="pb-4 pr-6 w-full lg:w-1/2" label="Identificador" />
          <TextInput v-model="editPozoForm.volumen_lts" :error="editPozoForm.errors.volumen_lts" class="pb-4 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
          <TextInput v-model="editPozoForm.presion_psi" :error="editPozoForm.errors.presion_psi" class="pb-4 pr-6 w-full lg:w-1/2" label="PSIA" />
          <TextInput v-model="editPozoForm.presion_kgcm2" :error="editPozoForm.errors.presion_kgcm2" class="pb-4 pr-6 w-full lg:w-1/2" label="KG/CM2" />
          <TextareaInput v-model="editPozoForm.observaciones" :error="editPozoForm.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editPozoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="pozos.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.deletePozo && pozos.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-pozos" v-model="selectAllPozos" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllPozos" />
                <label for="checkbox-all-pozos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">No.</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Identificador</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Fecha del Muestreo</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pozo in pozos.data" :key="pozo.id" class="bg-white border-b">
            <td class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span v-if="can.editPozo" class="inline-block whitespace-nowrap" title="Editar pozo">
                <button class="flex items-center mr-2" tabindex="-1" type="button" @click="openModalEditForm(pozo)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
              <span class="inline-block whitespace-nowrap" title="Ver pozo">
                <Link class="flex items-center" :href="`/pozos/${pozo.id}`" tabindex="-1">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="eye" />
                </Link>
              </span>
            </td>
            <td v-if="can.deletePozo" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-pozo-${pozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="pozo.id" @change="changeToggleAllPozos" />
                <label :for="`checkbox-pozo-${pozo.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ pozo.id }}</span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span> {{ pozo.nombre_pozo }}</span>
                <span v-if="pozo.deleted_at" title="Este pozo ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ pozo.identificador === '' || pozo.identificador === null ? 'Sin identificador.' : pozo.identificador }}</span>
            </td>
            <td class="px-6 py-4">
              <span class="block">{{ pozo.fecha_hora }}</span>
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
