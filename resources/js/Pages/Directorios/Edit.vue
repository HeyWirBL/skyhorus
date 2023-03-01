<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import TextInput from '@/Components/TextInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'

const props = defineProps({
  can: Object,
  directorioData: Object,
})

const swal = inject('$swal')

const form = useForm({
  nombre_dir: props.directorioData.nombre_dir,
  fecha_dir: props.directorioData.fecha_dir,
})

const update = () => form.put(`/directorios/${props.directorioData.id}`)

const destroy = () => {
  swal({
    title: '¿Estás seguro de querer eliminar esta carpeta?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(`/directorios/${props.directorioData.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer esta carpeta?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/directorios/${props.directorioData.id}/restore`)
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
    <TrashedMessage v-if="directorioData.deleted_at && can.restoreDirectorio" class="mb-6" @restore="restore">Esta carpeta ha sido eliminada.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre_dir" :error="form.errors.nombre_dir" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre de carpeta" />
          <TextInput v-model="form.fecha_dir" :error="form.errors.fecha_dir" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de creación" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!directorioData.deleted_at && can.deleteDirectorio" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Carpeta</button>
          <LoadingButton :loading="form.processing" :disabled="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
