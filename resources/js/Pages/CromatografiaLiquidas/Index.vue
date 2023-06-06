<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue'
import DropZone from '@/Components/DropZone.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

dayjs.extend(relativeTime)

const props = defineProps({
  can: Object,
  filters: Object,
  cromatografiaLiquidas: Object,
  pozos: Array,
})

const swal = inject('$swal')

const uploadNewDoc = ref(false)
const editUploadedDoc = ref(false)

const selected = ref([])
const selectAllCromLiq = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
  year: props.filters.year,
  month: props.filters.month,
})

const cromatografiaLiquidaForm = useForm({})

const uploadDocForm = useForm({
  documento: [],
  pozo_id: '',
  fecha_hora: '',
})

const editUploadedDocForm = useForm({
  _method: 'put',
  id: '',
  documento: [],
  pozo_id: '',
  fecha_hora: '',
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

const toggleAllCromLiq = () => {
  toggleAll(props.cromatografiaLiquidas.data, selected, selectAllCromLiq)
}
const changeToggleAllCromLiq = () => {
  changeToggleAll(props.cromatografiaLiquidas.data, selected, selectAllCromLiq)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const openModalUploadForm = () => (uploadNewDoc.value = true)

const openModalEditUploadedForm = (cromatografiaLiquida) => {
  // Set form field values
  editUploadedDocForm.id = cromatografiaLiquida.id
  editUploadedDocForm.pozo_id = cromatografiaLiquida.pozo_id
  editUploadedDocForm.fecha_hora = cromatografiaLiquida.fecha_hora

  if (Array.isArray(cromatografiaLiquida.documento)) {
    editUploadedDocForm.documento = cromatografiaLiquida.documento
  } else {
    editUploadedDocForm.documento = [cromatografiaLiquida.documento]
  }

  editUploadedDoc.value = true
}

const closeModalUploadForm = () => {
  uploadNewDoc.value = false
}

const closeModalEditUploadedForm = () => {
  editUploadedDoc.value = false
  editUploadedDocForm.reset()
}

const store = () => {
  uploadDocForm.post('/cromatografia-liquidas', {
    preserveScroll: true,
    onSuccess: () => {
      closeModalUploadForm()
      uploadDocForm.reset()
    },
  })
}

const update = () => {
  editUploadedDocForm.post(`/cromatografia-liquidas/${editUploadedDocForm.id}`, {
    preserveScroll: true,
    onSuccess: () => (editUploadedDoc.value = false),
    onError: (error) => console.error(error),
    onFinish: () => {
      if (!editUploadedDocForm.hasErrors) {
        editUploadedDocForm.reset()
      }
    },
  })
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
        cromatografiaLiquidaForm.delete(`/cromatografia-liquidas/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromLiq.value = false),
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
        cromatografiaLiquidaForm.delete(`/cromatografia-liquidas?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromLiq.value = false),
        })
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
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        cromatografiaLiquidaForm.put(`/cromatografia-liquidas/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromLiq.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos documentos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        cromatografiaLiquidaForm.put(`/cromatografia-liquidas?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllCromLiq.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/cromatografia-liquidas', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Cromatografía Líquida" />
    <h1 class="mb-8 text-3xl font-bold">Cromatografías Líquidas</h1>
    <div class="flex items-center mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <div class="mt-4">
          <label class="block text-gray-700">Año:</label>
          <input v-model="form.year" class="form-input mt-4" type="number" placeholder="YYYY" />
        </div>
        <label class="block mt-4 text-gray-700">Mes:</label>
        <select v-model="form.month" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="1">Enero</option>
          <option value="2">Febrero</option>
          <option value="3">Marzo</option>
          <option value="4">Abril</option>
          <option value="5">Mayo</option>
          <option value="6">Junio</option>
          <option value="7">Julio</option>
          <option value="8">Agosto</option>
          <option value="9">Septiembre</option>
          <option value="10">Octubre</option>
          <option value="11">Noviembre</option>
          <option value="12">Diciembre</option>
        </select>
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <button v-if="can.createCromatografiaLiquida" class="btn-yellow mr-2" type="button" @click="openModalUploadForm">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </button>
      <button v-if="can.deleteCromatografiaLiquida && cromatografiaLiquidas.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllCromLiq && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="can.restoreCromatografiaLiquida && cromatografiaLiquidas.data.length !== 0 && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllCromLiq && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Upload Documento Form Modal -->
    <Modal :show="uploadNewDoc">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Subir Documentos</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalUploadForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <form @submit.prevent="store">
        <div class="relative flex flex-wrap p-4">
          <div class="w-full pb-4">
            <!-- DropZone Component -->
            <DropZone v-model="uploadDocForm.documento" :errors="uploadDocForm.errors.documento" accept="application/pdf" label="Documento:" />
            <p class="mt-2 text-sm">
              <span class="font-semibold">Nota:</span>
              Solo subir archivos .pdf*
            </p>
          </div>
          <SelectInput v-model="uploadDocForm.pozo_id" :error="uploadDocForm.errors.pozo_id" class="pb-4 pr-6 w-full" label="Pozo/Instalación:">
            <option value="">Por favor seleccione</option>
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
          <TextInput v-model="uploadDocForm.fecha_hora" :error="uploadDocForm.errors.fecha_hora" class="pb-8 pr-6 w-full" type="date" label="Fecha" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="uploadDocForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalUploadForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Edit an Upload Documento Form Modal -->
    <Modal :show="editUploadedDoc">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Documentos [{{ editUploadedDocForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditUploadedForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <form @submit.prevent="update">
        <div class="relative flex flex-wrap p-4">
          <div class="w-full pb-4">
            <!-- DropZone Component -->
            <DropZone v-model="editUploadedDocForm.documento" :errors="editUploadedDocForm.errors.documento" accept="application/pdf" label="Documento:" />
            <p class="mt-2 text-sm">
              <span class="font-semibold">Nota:</span>
              Solo subir archivos .pdf*
            </p>
          </div>
          <SelectInput v-model="editUploadedDocForm.pozo_id" :error="editUploadedDocForm.errors.pozo_id" class="pb-4 pr-6 w-full" label="Pozo/Instalación:">
            <option value="">Por favor seleccione</option>
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
          <TextInput v-model="editUploadedDocForm.fecha_hora" :error="editUploadedDocForm.errors.fecha_hora" class="pb-8 pr-6 w-full" type="date" label="Fecha" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editUploadedDocForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditUploadedForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="can.editCromatografiaLiquida && cromatografiaLiquidas.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.deleteCromatografiaLiquida && cromatografiaLiquidas.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-cromliquidas" v-model="selectAllCromLiq" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllCromLiq" />
                <label for="checkbox-all-cromliquidas" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Archivo</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Pozo/Instalación</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Fecha</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cromatografiaLiquida in cromatografiaLiquidas.data" :key="cromatografiaLiquida.id" class="bg-white border-b">
            <td v-if="can.editCromatografiaLiquida" class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap" title="Editar documento">
                <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditUploadedForm(cromatografiaLiquida)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
            </td>
            <td v-if="can.deleteCromatografiaLiquida" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-cromliquida-${cromatografiaLiquida.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaLiquida.id" @change="changeToggleAllCromLiq" />
                <label :for="`checkbox-cromliquida-${cromatografiaLiquida.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div v-if="Array.isArray(cromatografiaLiquida.documento)">
                <div v-for="(file, index) in cromatografiaLiquida.documento" :key="index" class="flex items-center leading-snug">
                  <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/descargar/${index}`">
                    {{ file.usrName }}
                  </a>
                  <span class="text-xs ml-2"> {{ $filesize(file.size) }} </span>
                  <span v-if="cromatografiaLiquida.deleted_at" title="Esta documento ha sido eliminado.">
                    <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                  </span>
                </div>
              </div>
              <div v-else>
                <div class="flex items-center leading-snug">
                  <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/cromatografia-liquidas/${cromatografiaLiquida.id}/descargar`">
                    {{ cromatografiaLiquida.documento.usrName }}
                  </a>
                  <span class="text-xs ml-2"> {{ $filesize(cromatografiaLiquida.documento.size) }} </span>
                  <span v-if="cromatografiaLiquida.deleted_at" title="Esta documento ha sido eliminado.">
                    <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                  </span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ cromatografiaLiquida.pozo.nombre_pozo }}</span>
                <span v-if="cromatografiaLiquida.pozo.deleted_at" title="Este pozo ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ dayjs(cromatografiaLiquida.fecha_hora).format('DD/MM/YYYY') }}</span>
            </td>
          </tr>
          <tr v-if="cromatografiaLiquidas.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="cromatografiaLiquidas.links" :total="cromatografiaLiquidas.total" />
  </div>
</template>
