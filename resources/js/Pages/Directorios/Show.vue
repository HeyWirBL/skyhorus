<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import DropZone from '@/Components/DropZone.vue'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  directorioData: Object,
  anos: Array,
  meses: Array,
})

const swal = inject('$swal')

const uploadNewDoc = ref(false)
const editUploadedDoc = ref(false)

const selected = ref([])
const selectAllDocs = ref(false)

const form = ref({
  search: props.filters.search,
  year: props.filters.year,
  month: props.filters.month,
  trashed: props.filters.trashed,
})

const docForm = useForm({})

const uploadDocForm = useForm({
  documento: [],
  directorio_id: props.directorioData.id,
  ano_id: '',
  mes_detalle_id: '',
})

const editUploadedDocForm = useForm({
  _method: 'put',
  id: '',
  documento: [],
  directorio_id: '',
  ano_id: '',
  mes_detalle_id: '',
})

const documentos = computed(() => props.directorioData.documentos)
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

const toggleAllDocs = () => {
  toggleAll(documentos.value.data, selected, selectAllDocs)
}

const changeToggleAllDocs = () => {
  changeToggleAll(documentos.value.data, selected, selectAllDocs)
}

const openModalUploadForm = () => (uploadNewDoc.value = true)

const openModalEditUploadedForm = (documento) => {
  // Set form field values
  editUploadedDocForm.id = documento.id
  editUploadedDocForm.directorio_id = documento.directorio_id
  editUploadedDocForm.ano_id = documento.ano_id
  editUploadedDocForm.mes_detalle_id = documento.mes_detalle_id

  if (Array.isArray(documento.documento)) {
    editUploadedDocForm.documento = documento.documento
  } else {
    editUploadedDocForm.documento = [documento.documento]
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

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const store = () => {
  uploadDocForm.post('/documentos', {
    preserveScroll: true,
    onSuccess: () => {
      closeModalUploadForm()
      uploadDocForm.reset()
    },
  })
}

const update = () => {
  editUploadedDocForm.post(`/documentos/${editUploadedDocForm.id}`, {
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
        docForm.delete(`/documentos/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDocs.value = false),
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
        docForm.delete(`/documentos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDocs.value = false),
        })
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer a este documento?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        docForm.put(`/documentos/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDocs.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer a estos documentos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        docForm.put(`/documentos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDocs.value = false),
        })
      }
    })
  }
}

const restore = () => {
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
      docForm.put(`/directorios/${props.directorioData.id}/restore`)
    }
  })
}

watch(
  () => form.value,
  debounce(function () {
    router.get(`/directorios/${props.directorioData.id}`, pickBy(form.value), { preserveState: true, preserveScroll: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Carpeta" />

    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/directorios">Carpetas</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ directorioData.nombre_dir }} ({{ documentos.total }})
    </h1>

    <TrashedMessage v-if="directorioData.deleted_at && can.restoreDirectorio" class="mb-6" @restore="restore">Esta carpeta ha sido eliminada.</TrashedMessage>

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
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>

    <div class="flex items-center mb-6">
      <button class="btn-yellow mr-2" type="button" @click="openModalUploadForm">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </button>
      <button v-if="documentos.data.length !== 0 && !isTrashed" class="btn-secondary mr-2" type="button" :disabled="!selectAllDocs && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="documentos.data.length !== 0 && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllDocs && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

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
            <DropZone v-model="uploadDocForm.documento" :errors="uploadDocForm.errors.documento" label="Documento:" />
          </div>
          <div class="pb-4 pr-6 w-full">
            <label class="form-label" for="text-input-dir">Carpeta:</label>
            <div>
              <span class="block text-yellow-500">{{ directorioData.nombre_dir }}</span>
            </div>
            <input id="text-input-dir" type="hidden" :value="directorioData.id" />
          </div>
          <SelectInput v-model="uploadDocForm.ano_id" :error="uploadDocForm.errors.ano_id" class="pb-4 pr-6 w-full lg:w-1/2" label="Año">
            <option value="">Por favor seleccione</option>
            <option v-for="ano in anos" :key="ano.id" :value="ano.id">{{ ano.ano }}</option>
          </SelectInput>
          <SelectInput v-model="uploadDocForm.mes_detalle_id" :error="uploadDocForm.errors.mes_detalle_id" class="pb-4 pr-6 w-full lg:w-1/2" label="Mes">
            <option value="">Por favor seleccione</option>
            <option v-for="mes in meses" :key="mes.id" :value="mes.id">{{ mes.nombre }}</option>
          </SelectInput>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="uploadDocForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalUploadForm">Cancelar</button>
        </div>
      </form>
    </Modal>

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
            <DropZone v-model="editUploadedDocForm.documento" :errors="editUploadedDocForm.errors.documento" label="Documento:" />
          </div>
          <div class="pb-4 pr-6 w-full">
            <label class="form-label" for="text-input-dir">Carpeta:</label>
            <div>
              <span class="block text-yellow-500">{{ directorioData.nombre_dir }}</span>
            </div>
            <input id="text-input-dir" type="hidden" :value="directorioData.id" />
          </div>
          <SelectInput v-model="editUploadedDocForm.ano_id" :error="editUploadedDocForm.errors.ano_id" class="pb-4 pr-6 w-full lg:w-1/2" label="Año">
            <option value="">Por favor seleccione</option>
            <option v-for="ano in anos" :key="ano.id" :value="ano.id">{{ ano.ano }}</option>
          </SelectInput>
          <SelectInput v-model="editUploadedDocForm.mes_detalle_id" :error="editUploadedDocForm.errors.mes_detalle_id" class="pb-4 pr-6 w-full lg:w-1/2" label="Mes">
            <option value="">Por favor seleccione</option>
            <option v-for="mes in meses" :key="mes.id" :value="mes.id">{{ mes.nombre }}</option>
          </SelectInput>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editUploadedDocForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditUploadedForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="documentos.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="documentos.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-docs" v-model="selectAllDocs" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllDocs" />
                <label for="checkbox-all-docs" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Documento</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Carpeta</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Año</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Mes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="documento in documentos.data" :key="documento.id" class="bg-white border-b">
            <td class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap">
                <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditUploadedForm(documento)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
            </td>
            <td class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-doc-${documento.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="documento.id" @change="changeToggleAllDocs" />
                <label :for="`checkbox-doc-${documento.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div v-if="Array.isArray(documento.documento)">
                <div v-for="(file, index) in documento.documento" :key="index" class="flex items-center leading-snug">
                  <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/documentos/${documento.id}/descargar/${index}`">
                    {{ file.usrName }}
                  </a>
                  <span class="text-xs ml-2"> {{ $filesize(file.size) }} </span>
                  <span v-if="documento.deleted_at" title="Esta documento ha sido eliminado.">
                    <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                  </span>
                </div>
              </div>
              <div v-else>
                <div class="flex items-center leading-snug">
                  <a class="text-yellow-400 hover:underline focus:text-yellow-500" :href="`/documentos/${documento.id}/descargar`">
                    {{ documento.documento.usrName }}
                  </a>
                  <span class="text-xs ml-2"> {{ $filesize(documento.documento.size) }} </span>
                  <span v-if="documento.deleted_at" title="Esta documento ha sido eliminado.">
                    <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                  </span>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ directorioData.nombre_dir }}</span>
                <span v-if="directorioData.deleted_at" title="Esta carpeta ha sido eliminada.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ documento.ano.ano }}</span>
                <span v-if="documento.ano.deleted_at" title="Esta año ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ documento.mes.nombre }}</span>
            </td>
          </tr>
          <tr v-if="documentos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="documentos.links" :total="documentos.total" />
  </div>
</template>
