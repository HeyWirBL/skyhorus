<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

const props = defineProps({
  usuarios: Object,
})

const selected = ref([])
const selectAll = ref(false)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.usuarios.data) {
      selected.value.push(props.usuarios.data[i].idUsuario)
    }
  }
}
</script>

<template>
  <div>
    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold">Usuarios</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter class="mr-4 w-full max-w-md">
        <label class="block text-gray-700">Rol:</label>
        <select class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="Usuario">Usuario</option>
          <option value="Administrador">Administrador</option>
        </select>
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow" href="/usuarios/create">
        <span>Añadir</span>
        <span class="hidden md:inline">&nbsp;Usuario</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-users" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-users" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Nombre</th>
            <th scope="col" class="px-6 py-3">Apellidos</th>
            <th scope="col" class="px-6 py-3">Usuario</th>
            <th scope="col" class="px-6 py-3">Email</th>
            <th scope="col" colspan="2" class="px-6 py-3">Rol</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="usuario in props.usuarios.data" :key="usuario.idUsuario" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-user-${usuario.idUsuario}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="usuario.idUsuario" />
                <label :for="`checkbox-user-${usuario.idUsuario}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                {{ usuario.nombre }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                {{ usuario.apellidos }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                {{ usuario.user.username }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                {{ usuario.user.email }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                {{ usuario.rol }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/usuarios/${usuario.idUsuario}/edit`" tabindex="-1">
                <svg class="block w-6 h-6 fill-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707" /></svg>
              </Link>
            </td>
          </tr>
          <tr v-if="props.usuarios.data.length === 0">
            <td class="px-6 py-4">No se encontraron usuarios registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="pt-4" :links="props.usuarios.links" :total="props.usuarios.total" />
  </div>
</template>
