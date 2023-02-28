<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  can: Object,
  user: Object,
})

const swal = inject('$swal')

const form = useForm({
  _method: 'put',
  nombre: props.user.nombre,
  apellidos: props.user.apellidos,
  usuario: props.user.usuario,
  email: props.user.email,
  password: '',
  telefono: props.user.telefono,
  direccion: props.user.direccion,
  rol: props.user.rol,
})

const update = () => {
  form.post(`/users/${props.user.id}`, {
    onSuccess: () => form.reset('password'),
  })
}

const destroy = () => {
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
      form.delete(`/users/${props.user.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer a este usuario?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/users/${props.user.id}/restore`)
    }
  })
}
</script>

<template>
  <div>
    <Head :title="`${form.nombre} ${form.apellidos}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/users">Usuarios</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ form.nombre }} {{ form.apellidos }}
    </h1>
    <TrashedMessage v-if="user.deleted_at && can.restoreUser" class="mb-6" @restore="restore">Este usuario ha sido eliminado.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre" />
          <TextInput v-model="form.apellidos" :error="form.errors.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos" />
          <TextInput v-model="form.usuario" :error="form.errors.usuario" class="pb-8 pr-6 w-full lg:w-1/2" label="Usuario" />
          <TextInput v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Correo electrónico" />
          <TextInput v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Contraseña" />
          <TextInput v-model="form.telefono" :error="form.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Teléfono" />
          <TextInput v-model="form.direccion" :error="form.errors.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dirección" />
          <SelectInput v-model="form.rol" :error="form.errors.rol" class="pb-8 pr-6 w-full lg:w-1/2" label="Rol">
            <option value="">Por favor seleccione</option>
            <option value="Administrador">Administrador</option>
            <option value="Usuario">Usuario</option>
            <option value="Colaborador">Colaborador</option>
            <option value="Consultor">Consultor</option>
            <option value="Editor">Editor</option>
          </SelectInput>
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!user.deleted_at && can.deleteUser" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Usuario</button>
          <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
