<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import debounce from 'lodash/debounce'
import { watch, ref } from 'vue'
import pickBy from 'lodash/pickBy'

const props = defineProps({
  directorio: Object,
  anos: Object,
  mes: Object,
})

const form = useForm({
  _method: 'put',
  nombre_dir: props.directorio.nombre_dir,
  fecha_dir: props.directorio.fecha_dir,
  deleted_at: props.directorio.deleted_at,
})

const f = ref({
  search: props.directorio.documentos.filter.search,
  trashed: props.directorio.documentos.filter.trashed,
});

const df = ref({
  year: props.directorio.documentos.datefilter,
  month: props.directorio.documentos.datefilter,
}); 

watch(() => f.value,
debounce(function(){
  router.get(`/directorios/${props.directorio.id}/editar`, pickBy(f.value), {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}, 150),
{
  deep: true,
}
);

watch(df.value, function(){
  router.get(`/directorios/${props.directorio.id}/editar`, df.value, {
    preserveState: true,
    replace: true, 
    preserveScroll: true,
  })
});

const update = () => form.put(`/directorios/${props.directorio.id}`)

const destroy = () => {
  if (confirm('¿Estás seguro de querer eliminar esta carpeta?')) {
    form.delete(`/directorios/${props.directorio.id}`)
  }
}

const restore = () => {
  if (confirm('¿Estás seguro de querer restablecer esta carpeta?')) {
    form.put(`/directorios/${props.directorio.id}/restore`)
  }
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
    <div class="flex mt-6">
      <SearchFilter v-model="f.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="f.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <div class="flex ml-20">
        <div class="flex flex-col mx-10">
          <label for="">Filtrar por año</label>
          <select v-model="df.year" class="mr-4 max-w-md h-8">
            <option value=""></option>
            <option v-for="ano in props.anos" :value="ano.id">{{ ano.ano }}</option>
          </select>
       </div>
        <div class="flex flex-col mx-10">
          <label for="">Filtrar por mes</label>
          <select v-model="df.month" class="mr-4 max-w-md h-8">
            <option value=""></option>
            <option v-for="mes in props.mes" :value="mes.id">{{ mes.nombre }}</option>
          </select>
        </div>
      </div>
      
    </div>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
              <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6">
                  <div class="flex items-center">
                    <input
                      v-model="selectAll"
                      id="checkbox-all"
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      @click="select"
                    />
                    <label for="checkbox-all" class="sr-only"
                      >Seleccionar todos los elementos</label
                    >
                  </div>
                </th>
                <th class="pb-4 pt-6 px-6">Documentos</th>
                <th class="pb-4 pt-6 px-6">Mes</th>
                <th class="pb-4 pt-6 px-6" colspan="2">Año</th>
              </tr>
              <tr
                v-for="documento in props.directorio.documentos"
                :key="documento.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="w-4 pb-4 pt-6 px-6 border-t">
                  <div class="flex items-center">
                    <input
                      v-model="selected"
                      id="checkbox-table-search-1"
                      type="checkbox"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                      :value="documento.id"
                    />
                    <label for="checkbox-table-search-1" class="sr-only"
                      >Seleccionar elemento</label
                    >
                  </div>
                </td>
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4 focus:text-yellow-500"
                    :href="`/documents/${documento.id}`"
                    >{{ documento.Nombre }}</Link
                  >
                </td>
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4 focus:text-yellow-500"
                    :href="`/document/${documento.id}/edit`"
                  >
                    {{ documento.mes_id }}</Link
                  >
                </td>
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4 focus:text-yellow-500"
                    :href="`/document/${documento.id}/edit`"
                  >
                    {{ documento.ano_id }}
                  </Link>
                </td>
                <td class="w-px border-t">
                  <Link
                    class="flex items-center px-4"
                    :href="`/document/${documento.id}/edit`"
                    tabindex="-1"
                  >
                    <Icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                  </Link>
                </td>
              </tr>
              <tr v-if="props.directorio.documentos.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                  No se encontraron documentos en esta carpeta.
                </td>
              </tr>
          </table>
      </div>
  </div>
</template>
