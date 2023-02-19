<script setup>
import { inject, ref, watch } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  filters: Object,
  directorio: Object,
  anos: Array,
  meses: Array,
})

const swal = inject('$swal')

const selected = ref([])
const selectAll = ref(false)

const form = useForm({
  nombre_dir: props.directorio.nombre_dir,
  fecha_dir: props.directorio.fecha_dir,
})

const search = ref({
  search: props.filters.search,
  year: props.filters.year,
  month: props.filters.month,
  trashed: props.filters.trashed,
})

watch(
  () => search.value,
  debounce(function () {
    router.get(`/directorios/${props.directorio.id}/editar`, pickBy(search.value), { preserveState: true, preserveScroll: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.directorio.documentos.data) {
      selected.value.push(props.directorio.documentos.data[i].id)
    }
  }
}

const reset = () => {
  search.value = mapValues(search.value, () => null)
}

const update = () => form.put(`/directorios/${props.directorio.id}`)

const destroy = () => {
  swal({
    title: '¿Estás seguro de querer eliminar esta carpeta?',
    text: 'Al hacer clic en el botón de confirmar estarás enviando esta carpeta al modo "Solo Eliminado".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(`/directorios/${props.directorio.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer esta carpeta?',
    text: 'Esta carpeta se restablecerá del modo "Solo Eliminado" y pasará al estado "Con Modificación".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/directorios/${props.directorio.id}/restore`)
    }
  })
}
</script>

<template>
  <div>
    <Head :title="form.nombre_dir" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/directorios">Carpetas</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ form.nombre_dir }}
    </h1>
    <TrashedMessage v-if="props.directorio.deleted_at" class="mb-6" @restore="restore">Esta carpeta ha sido eliminada.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre_dir" :error="form.errors.nombre_dir" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre de carpeta" />
          <TextInput v-model="form.fecha_dir" :error="form.errors.fecha_dir" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de creación" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!props.directorio.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Carpeta</button>
          <LoadingButton :loading="form.processing" :disabled="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
    <h2 class="mt-12 mb-8 text-2xl font-bold">Documentos</h2>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="search.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Año:</label>
        <select v-model="search.year" class="form-select mt-1 w-full">
          <option :value="null" />
          <option v-for="ano in anos" :key="ano.id" :value="ano.id">{{ ano.ano }}</option>
        </select>
        <label class="block mt-4 text-gray-700">Mes:</label>
        <select v-model="search.month" class="form-select mt-1 w-full">
          <option :value="null" />
          <option v-for="mes in meses" :key="mes.id" :value="mes.id">{{ mes.nombre }}</option>
        </select>
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="search.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow" href="/documentos/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-documentos" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-documentos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="px-6 py-3">Documento</th>
            <th scope="col" class="px-6 py-3">Año</th>
            <th scope="col" class="px-6 py-3" colspan="2">Mes</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="documento in directorio.documentos.data" :key="documento.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-pozo-${documento.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="documento.id" />
                <label :for="`checkbox-pozo-${documento.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/documentos/${documento.id}/editar`">
                {{ documento.documento }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/documentos/${documento.id}/editar`" tabindex="-1">
                {{ documento.ano.ano }}
              </Link>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/documentos/${documento.id}/editar`" tabindex="-1">{{ documento.mes.nombre }} </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/documentos/${documento.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="directorio.documentos.data.length === 0">
            <td class="px-6 py-4" colspan="5">No se encontraron documentos en este directorio.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="directorio.documentos.links" :total="directorio.documentos.total" />
  </div>
</template>
