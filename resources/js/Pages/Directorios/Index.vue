<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  directorios: Object,
})

const swal = inject('$swal')

const createNewDir = ref(false)
const editDir = ref(false)

const selected = ref([])
const selectAllDir = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

const dirForm = useForm({})

const createDirForm = useForm({
  nombre_dir: '',
  fecha_dir: '',
})

const editDirForm = useForm({
  _method: 'put',
  id: '',
  nombre_dir: '',
  fecha_dir: '',
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

const toggleAllDir = () => {
  toggleAll(props.directorios.data, selected, selectAllDir)
}

const changeToggleAllDir = () => {
  changeToggleAll(props.directorios.data, selected, selectAllDir)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const openModalCreateForm = () => {
  createNewDir.value = true
}

const openModalEditForm = (dir) => {
  // Set the form values
  editDirForm.id = dir.id
  editDirForm.nombre_dir = dir.nombre_dir
  editDirForm.fecha_dir = dir.fecha_dir
  editDir.value = true
}

const closeModalCreateForm = () => {
  createNewDir.value = false
  createDirForm.reset()
}

const closeModalEditForm = () => {
  editDir.value = false
  editDirForm.reset()
}

const store = () => {
  createDirForm.post('/directorios', {
    preserveScroll: true,
    onSuccess: () => closeModalCreateForm(),
  })
}

const update = () => {
  editDirForm.post(`/directorios/${editDirForm.id}`, {
    preserveScroll: true,
    onSuccess: () => closeModalEditForm(),
    onFinish: () => {
      if (!editDirForm.hasErrors) {
        editDirForm.reset()
      }
    },
  })
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este directorio?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        dirForm.delete(`/directorios/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos directorios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        dirForm.delete(`/directorios?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer este directorio?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        dirForm.put(`/directorios/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos directorios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        dirForm.put(`/directorios?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllDir.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/directorios', pickBy(form.value), { preserveState: true, replace: true })
  }, 150),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Directorios" />
    <h1 class="mb-8 text-3xl font-bold">Carpetas</h1>
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
      <button v-if="can.createDirectorio" class="btn-yellow mr-2" type="button" @click="openModalCreateForm">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Directorio</span>
      </button>
      <button v-if="can.deleteDirectorio && directorios.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllDir && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="can.restoreDirectorio && directorios.data.length !== 0 && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllDir && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Create Directorio Form Modal -->
    <Modal :show="createNewDir">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Crear Directorio</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalCreateForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="createDirForm.nombre_dir" :error="createDirForm.errors.nombre_dir" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre de carpeta" />
          <TextInput v-model="createDirForm.fecha_dir" :error="createDirForm.errors.fecha_dir" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de creación" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="createDirForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalCreateForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Edit Directorio Form Modal -->
    <Modal :show="editDir">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Directorio [{{ editDirForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="editDirForm.nombre_dir" :error="editDirForm.errors.nombre_dir" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre de carpeta" />
          <TextInput v-model="editDirForm.fecha_dir" :error="editDirForm.errors.fecha_dir" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de creación" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editDirForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="can.editDirectorio && directorios.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.deleteDirectorio && directorios.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-directorios" v-model="selectAllDir" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllDir" />
                <label for="checkbox-all-directorios" class="sr-only">checkbox</label>
              </div>
            </th>
            <th v-if="directorios.data.length !== 0" scope="col" class="px-6 py-3 border-solid border border-gray-200" />
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Nombre de carpeta</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Fecha de creación</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="dir in directorios.data" :key="dir.id" class="bg-white border-b">
            <td v-if="can.editDirectorio" class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap" title="Editar directorio">
                <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditForm(dir)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
            </td>
            <td v-if="can.deleteDirectorio" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-directorio-${dir.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="dir.id" @change="changeToggleAllDir" />
                <label :for="`checkbox-directorio-${dir.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap">
                <Link class="text-yellow-500 hover:underline" :href="`/directorios/${dir.id}`"> Archivos ({{ dir.documentos.length }}) </Link>
              </span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ dir.nombre_dir }}</span>
                <span v-if="dir.deleted_at" title="Esta carpeta ha sido eliminada.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span>{{ dir.fecha_dir }}</span>
            </td>
          </tr>
          <tr v-if="directorios.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron carpetas {{ form.trashed === 'only' ? 'eliminadas' : 'registradas' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="pt-4" :links="directorios.links" :total="directorios.total" />
  </div>
</template>
