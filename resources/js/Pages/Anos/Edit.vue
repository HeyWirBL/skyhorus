<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  ano: Object,
})

const swal = inject('$swal')

const form = useForm({
  _method: 'put',
  ano: props.ano.ano,
})

const update = () => {
  form.post(`/anos/${props.ano.id}`)
}

const destroy = () => {
  swal({
    title: '¿Estás seguro de querer eliminar este año?',
    text: 'Al hacer clic en el botón de confirmar estarás enviando esta año al modo "Solo Eliminado".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(`/anos/${props.ano.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer este año?',
    text: 'Este año se restablecerá del modo "Solo Eliminado" y pasará al estado "Con Modificación".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/anos/${props.ano.id}/restore`)
    }
  })
}
</script>

<template>
  <div>
    <Head :title="`Año ${form.ano}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/anos">Años</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ form.ano }}
    </h1>
    <TrashedMessage v-if="props.ano.deleted_at" class="mb-6" @restore="restore">Este año ha sido eliminado.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.ano" :error="form.errors.ano" class="pb-8 pr-6 w-full lg:w-1/2" label="Año" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!props.ano.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Año</button>
          <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
