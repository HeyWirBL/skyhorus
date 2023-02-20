<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'

const user = usePage().props.auth.user

const form = useForm({
  _method: 'put',
  nombre: user.nombre,
  apellidos: user.apellidos,
  usuario: user.usuario,
  email: user.email,
  telefono: user.telefono,
  direccion: user.direccion,
})

const update = () => {
  form.post(`/perfil/${user.id}`)
}
</script>

<template>
  <div>
    <h2 class="text-lg font-bold px-8 pt-8 pb-2 -mb-8">Información del Perfil</h2>
    <p class="text-sm text-gray-600 px-8 py-8 -mb-8">Actualice la información de perfil y la dirección de correo electrónico de su cuenta.</p>
    <form @submit.prevent="update">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <TextInput v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre" />
        <TextInput v-model="form.apellidos" :error="form.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos" />
        <TextInput v-model="form.usuario" :error="form.errors.usuario" class="pb-8 pr-6 w-full lg:w-1/2" label="Usuario" />
        <TextInput v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Correo electrónico" />
        <TextInput v-model="form.telefono" :error="form.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Teléfono" />
        <TextInput v-model="form.direccion" :error="form.errors.direccion" class="pb-8 pr-6 w-full lg:w-1/2" label="Dirección" />
      </div>
      <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
        <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Guardar</LoadingButton>
      </div>
    </form>
  </div>
</template>
