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
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  users: Object,
})

const swal = inject('$swal')

const createNewUser = ref(false)
const editUser = ref(false)

const selected = ref([])
const selectAllUsers = ref(false)

const form = ref({
  search: props.filters.search,
  role: props.filters.role,
  trashed: props.filters.trashed,
})

/* Empty Form for Deleting */
const userForm = useForm({})

/* Form for creating new user */
const createUserForm = useForm({
  nombre: '',
  apellidos: '',
  usuario: '',
  email: '',
  password: '',
  telefono: null,
  direccion: null,
  rol: '',
})

/* Form for editing a user */ 
const editUserForm = useForm({
  _method: 'put',
  id: '',
  nombre: '',
  apellidos: '',
  usuario: '',
  email: '',
  password: '',
  telefono: '',
  direccion: '',
  rol: '',
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

const toggleAllUsers = () => {
  toggleAll(props.users.data, selected, selectAllUsers)
}

const changeToggleAllUsers = () => {
  changeToggleAll(props.users.data, selected, selectAllUsers)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const openModalCreateForm = () => {
  createNewUser.value = true
}

const openModalEditForm = (user) => {
  // Set form field values
  editUserForm.id = user.id
  editUserForm.nombre = user.nombre
  editUserForm.apellidos = user.apellidos
  editUserForm.usuario = user.usuario
  editUserForm.email = user.email
  editUserForm.password = ''
  editUserForm.telefono = user.telefono
  editUserForm.direccion = user.direccion
  editUserForm.rol = user.rol

  editUser.value = true
}

const closeModalCreateForm = () => {
  createNewUser.value = false
  createUserForm.reset()
}

const closeModalEditForm = () => {
  editUser.value = false
  editUserForm.reset()
}

const store = () => {
  createUserForm.post('/users', {
    preserveScroll: true,
    onSuccess: () => closeModalCreateForm(),
  })
}

const update = () => {
  editUserForm.post(`/users/${editUserForm.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      editUserForm.reset('password')
      closeModalEditForm()
    },
    onFinish: () => {
      if (!editUserForm.hasErrors) {
        editUserForm.reset()
      }
    },
  })
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar a este usuario?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        userForm.delete(`/users/${selected.value}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllUsers.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar a estos usuarios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        userForm.delete(`/users?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllUsers.value = false),
        })
      }
    })
  }
}

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer a este usuario?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        userForm.put(`/users/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllUsers.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer a estos usuarios?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        userForm.put(`/users?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllUsers.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/users', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold">Usuarios</h1>
    <div class="flex items-center mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Rol:</label>
        <select v-model="form.role" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="Usuario">Usuario</option>
          <option value="Administrador">Administrador</option>
          <option value="Colaborador">Colaborador</option>
          <option value="Consultor">Consultor</option>
          <option value="Editor">Editor</option>
        </select>
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
          usuarios
        </select>
      </SearchFilter>
    </div>

    <div class="flex items-center mb-6">
      <button v-if="can.createUser" class="btn-yellow mr-2" type="button" @click="openModalCreateForm">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Usuario</span>
      </button>
      <button v-if="can.deleteUser && users.data.length !== 0 && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllUsers && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="can.restoreUser && users.data.length !== 0 && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllUsers && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Create User Form Modal -->
    <Modal :show="createNewUser">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Crear Usuario</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalCreateForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="store">
        <!-- Inputs -->
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="createUserForm.nombre" :error="createUserForm.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre" />
          <TextInput v-model="createUserForm.apellidos" :error="createUserForm.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos" />
          <TextInput v-model="createUserForm.usuario" :error="createUserForm.errors.usuario" class="pb-8 pr-6 w-full lg:w-1/2" label="Usuario" />
          <TextInput v-model="createUserForm.email" :error="createUserForm.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Correo electrónico" />
          <TextInput v-model="createUserForm.password" :error="createUserForm.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Contraseña" />
          <TextInput v-model="createUserForm.telefono" :error="createUserForm.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Teléfono" />
          <TextInput v-model="createUserForm.direccion" :error="createUserForm.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dirección" />
          <SelectInput v-model="createUserForm.rol" :error="createUserForm.errors.rol" class="pb-8 pr-6 w-full lg:w-1/2" label="Rol">
            <option value="">Por favor seleccione</option>
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
            <option value="Colaborador">Colaborador</option>
            <option value="Consultor">Consultor</option>
            <option value="Editor">Editor</option>
          </SelectInput>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="createUserForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalCreateForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Edit User Form Modal -->
    <Modal :show="editUser">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Usuario [{{ editUserForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="editUserForm.nombre" :error="editUserForm.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre" />
          <TextInput v-model="editUserForm.apellidos" :error="editUserForm.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos" />
          <TextInput v-model="editUserForm.usuario" :error="editUserForm.errors.usuario" class="pb-8 pr-6 w-full lg:w-1/2" label="Usuario" />
          <TextInput v-model="editUserForm.email" :error="editUserForm.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Correo electrónico" />
          <TextInput v-model="editUserForm.password" :error="editUserForm.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Contraseña" />
          <TextInput v-model="editUserForm.telefono" :error="editUserForm.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Teléfono" />
          <TextInput v-model="editUserForm.direccion" :error="editUserForm.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dirección" />
          <SelectInput v-model="editUserForm.rol" :error="editUserForm.errors.rol" class="pb-8 pr-6 w-full lg:w-1/2" label="Rol">
            <option value="">Por favor seleccione</option>
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
            <option value="Colaborador">Colaborador</option>
            <option value="Consultor">Consultor</option>
            <option value="Editor">Editor</option>
          </SelectInput>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editUserForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b-2">
          <tr>
            <th v-if="can.viewUser && users.data.length !== 0" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.deleteUser && users.data.length !== 0" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input id="checkbox-all-users" v-model="selectAllUsers" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllUsers" />
                <label for="checkbox-all-users" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Nombre</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Apellidos</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Usuario</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Email</th>
            <th scope="col" class="px-6 py-3 border-solid border border-gray-200">Rol</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id" class="bg-white border-b">
            <td class="px-6 py-4 whitespace-nowrap border-solid border border-gray-200">
              <span v-if="can.editUser" class="inline-block whitespace-nowrap" title="Editar usuario">
                <button class="flex items-center mr-2" tabindex="-1" type="button" @click="openModalEditForm(user)">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                </button>
              </span>
              <span v-if="can.viewUser" class="inline-block whitespace-nowrap" title="Ver usuario">
                <Link class="flex items-center" :href="`/users/${user.id}`" tabindex="-1">
                  <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="eye" />
                </Link>
              </span>
            </td>
            <td v-if="can.deleteUser" class="w-4 p-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <input :id="`checkbox-user-${user.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="user.id" @change="changeToggleAllUsers" />
                <label :for="`checkbox-user-${user.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <div class="flex items-center">
                <span> {{ user.nombre }}</span>
                <span v-if="user.deleted_at" title="Este usuario ha sido eliminado.">
                  <Icon class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
                </span>
              </div>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ user.apellidos }}</span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ user.usuario }}</span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ user.email }}</span>
            </td>
            <td class="px-6 py-4 border-solid border border-gray-200">
              <span class="block">{{ user.rol }}</span>
            </td>
          </tr>
          <tr v-if="users.data.length === 0">
            <td class="px-6 py-4" colspan="7">No se encontraron usuarios {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="users.links" :total="users.total" />
  </div>
</template>
