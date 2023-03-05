<script setup>
import { computed, inject, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue'
import DropZone from '@/Components/DropZone.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const swal = inject('$swal')

const uploadNewDoc = ref(false)
const editUploadedDoc = ref(false)

const selected = ref([])
const selectAllCromLiquida = ref(false)

const cromatografiaLiquidaForm = useForm({})

const uploadDocForm = useForm({
  documento: [],
  pozo_id: props.pozo.id,
  fecha_hora: '',
})

const editUploadedDocForm = useForm({
  _method: 'put',
  id: '',
  documento: [],
  pozo_id: '',
  fecha_hora: '',
})

const cromatografiaLiquidas = computed(() => props.pozo.cromatografiaLiquidas)

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
  toggleAll(cromatografiaLiquidas.value.data, selected, selectAllCromLiquida)
}

const changeToggleAllCromLiq = () => {
  changeToggleAll(cromatografiaLiquidas.value.data, selected, selectAllCromLiquida)
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

const openModalUploadForm = () => (uploadNewDoc.value = true)

const openModalEditUploadedForm = (cromatografiaLiquida) => {
  // Set form field values
  editUploadedDocForm.id = cromatografiaLiquida.id
  editUploadedDocForm.documento = [cromatografiaLiquida.documento]
  editUploadedDocForm.pozo_id = props.pozo.id
  editUploadedDocForm.fecha_hora = cromatografiaLiquida.fecha_hora

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
          onFinish: () => (selectAllCromLiquida.value = false),
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
          onFinish: () => (selectAllCromLiquida.value = false),
        })
      }
    })
  }
}
</script>

<template>
  <div>
    <h2 class="mb-8 text-2xl font-bold">Cromatografía Líquida</h2>
    <div class="flex items-center mb-6">
      <button v-if="can.createCromatografiaLiquida" class="btn-yellow mr-2" type="button" @click="openModalUploadForm">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </button>
      <button v-if="can.deleteCromatografiaLiquida && cromatografiaLiquidas.data.length !== 0" class="btn-secondary" type="button" :disabled="!selectAllCromLiquida && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
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
          <div class="pb-4 pr-6 w-full">
            <label class="form-label" for="text-input-pozo">Pozo/Instalación:</label>
            <div class="pt-2">
              <span class="block text-yellow-500">{{ pozo.nombre_pozo }}</span>
            </div>
            <input id="text-input-pozo" type="hidden" :value="pozo.id" />
          </div>
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
          <div class="pb-4 pr-6 w-full">
            <label class="form-label" for="text-input-pozo">Pozo/Instalación:</label>
            <div class="pt-2">
              <span class="block text-yellow-500">{{ pozo.nombre_pozo }}</span>
            </div>
            <input id="text-input-pozo" type="hidden" :value="pozo.id" />
          </div>
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
                <input id="checkbox-all-cromliquidas" v-model="selectAllCromLiquida" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllCromLiq" />
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
                  <span class="text-xs ml-2"> {{ filesize(file.size) }} </span>
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
                  <span class="text-xs ml-2"> {{ filesize(cromatografiaLiquida.documento.size) }} </span>
                  <span v-if="cromatografiaLiquida.deleted_at" title="Esta documento ha sido eliminado.">
                    <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                  </span>
                </div>
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
              <span class="block">{{ cromatografiaLiquida.fecha_hora }}</span>
            </td>
          </tr>
          <tr v-if="cromatografiaLiquidas.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="cromatografiaLiquidas.links" :total="cromatografiaLiquidas.total" />
  </div>
</template>
