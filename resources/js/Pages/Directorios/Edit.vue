<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  directorio: Object,
})

const form = useForm({
  _method: 'put',
  nombre_dir: props.directorio.nombre_dir,
  fecha_dir: props.directorio.fecha_dir,
  deleted_at: props.directorio.deleted_at,
})

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
  </div>
</template>
