<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  can: Object,
  filters: Object,
  users: Object,
})

const form = ref({
  search: props.filters.search,
  role: props.filters.role,
  trashed: props.filters.trashed,
})

watch(
  () => form.value,
  debounce(function () {
    router.get('/users', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const reset = () => {
  form.value = mapValues(form.value, () => null)
}
</script>

<template>
  <div>
    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold">Usuarios</h1>
    <div class="flex items-center justify-between mb-6">
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
      <Link v-if="can.createUser" class="btn-yellow" href="/users/crear">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Usuario</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="px-6 py-3">Nombre</th>
            <th scope="col" class="px-6 py-3">Apellidos</th>
            <th scope="col" class="px-6 py-3">Usuario</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" colspan="2" class="px-6 py-3">Rol</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users.data" :key="user.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td>
              <Link v-if="can.editUser" class="flex items-center px-6 py-4" :href="`/users/${user.id}/editar`" tabindex="-1">
                {{ user.nombre }}
                <Icon v-if="user.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
              </Link>
              <div v-else class="flex items-center px-6 py-4">
                {{ user.nombre }}
                <Icon v-if="user.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-yellow-400" name="trash" />
              </div>
            </td>
            <td>
              <Link v-if="can.editUser" class="flex items-center px-6 py-4" :href="`/users/${user.id}/editar`" tabindex="-1">
                {{ user.apellidos }}
              </Link>
              <div v-else class="flex items-center px-6 py-4">
                {{ user.apellidos }}
              </div>
            </td>
            <td>
              <Link v-if="can.editUser" class="flex items-center px-6 py-4" :href="`/users/${user.id}/editar`" tabindex="-1">
                {{ user.usuario }}
              </Link>
              <div v-else class="flex items-center px-6 py-4">
                {{ user.usuario }}
              </div>
            </td>
            <td>
              <Link v-if="can.editUser" class="flex items-center px-6 py-4" :href="`/users/${user.id}/editar`" tabindex="-1">
                {{ user.email }}
              </Link>
              <div v-else class="flex items-center px-6 py-4">
                {{ user.email }}
              </div>
            </td>
            <td>
              <Link v-if="can.editUser" class="flex items-center px-6 py-4" :href="`/users/${user.id}/editar`" tabindex="-1">
                {{ user.rol }}
              </Link>
              <div v-else class="flex items-center px-6 py-4">
                {{ user.rol }}
              </div>
            </td>
            <td class="w-px">
              <Link v-if="can.editUser" class="flex items-center px-6" :href="`/users/${user.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
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
