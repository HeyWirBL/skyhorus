<script setup>
import { computed, inject, ref, watch } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  anos: Object,
})

const swal = inject('$swal')

const createNewAno = ref(false)
const editAno = ref(false)

const selected = ref([])
const selectAllAno = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

/* Empty Form for Deleting */
const anoForm = useForm({})

const createAnoForm = useForm({
  ano: '',
})

const editAnoForm = useForm({
  _method: 'put',
  id: '',
  ano: '',
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

const toggleAllAno = () => {
  toggleAll(props.anos.data, selected, selectAllAno)
}

const changeToggleAllAno = () => {
  changeToggleAll(props.anos.data, selected, selectAllAno)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const openModalCreateAno = () => {
  createNewAno.value = true
}

const openModalEditAno = (ano) => {
  // Set the form values
  editAnoForm.id = ano.id
  editAnoForm.ano = ano.ano

  editAno.value = true
}

const closeModalCreateForm = () => {
  createNewAno.value = false
  createAnoForm.reset()
}

const closeModalEditForm = () => {
  editAno.value = false
  editAnoForm.reset()
}

const store = () => {
  createAnoForm.post('/anos', {
    preserveScroll: true,
    onSuccess: () => closeModalCreateForm(),
  })
}

const update = () => {
  editAnoForm.post(`/anos/${editAnoForm.id}`, {
    preserveScroll: true,
    onSuccess: () => closeModalEditForm(),
    onFinish: () => {
      if (!editAnoForm.hasErrors) {
        editAnoForm.reset()
      }
    },
  })
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar este año?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        anoForm.delete(`/anos/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllAno.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos años?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        anoForm.delete(`/anos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllAno.value = false),
        })
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer este año?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        anoForm.put(`/anos/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllAno.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer estos años?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        anoForm.put(`/anos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllAno.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/anos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Años" />
    <h1 class="mb-8 text-3xl font-bold">Años</h1>
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
      <button v-if="can.createAno" class="btn-yellow mr-2" type="button" @click="openModalCreateAno">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Año</span>
      </button>
      <button v-if="can.deleteAno && anos.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllAno && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="can.restoreAno && anos.data.length !== 0 && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllAno && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Create Ano Form Modal -->
    <Modal :show="createNewAno">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Crear Año</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalCreateForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="createAnoForm.ano" :error="createAnoForm.errors.ano" class="pb-8 pr-6 w-full" label="Año" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="createAnoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalCreateForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Edit Ano Form Modal -->
    <Modal :show="editAno">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Año [{{ editAnoForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="editAnoForm.ano" :error="editAnoForm.errors.ano" class="pb-8 pr-6 w-full" label="Año" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editAnoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="can.editAno && anos.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.deleteAno && anos.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-anos" v-model="selectAllAno" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllAno" />
                <label for="checkbox-all-anos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Año</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ano in anos.data" :key="ano.id" class="bg-white border-b">
            <td v-if="can.editAno" class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span class="inline-block whitespace-nowrap" title="Editar año">
                <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditAno(ano)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
            </td>
            <td v-if="can.deleteAno" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-ano-${ano.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="ano.id" @change="changeToggleAllAno" />
                <label :for="`checkbox-ano-${ano.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span>{{ ano.ano }}</span>
                <span v-if="ano.deleted_at" title="Esta año ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
          </tr>
          <tr v-if="anos.data.length === 0">
            <td class="px-6 py-4" colspan="3">No se encontraron años {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="anos.links" :total="anos.total" />
  </div>
</template>
