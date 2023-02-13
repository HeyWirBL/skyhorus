<script setup>
import { reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'

const roles = [
  { value: 'Administrador', type: String },
  { value: 'Usuario', type: String },
  { value: 'Colaborador', type: String },
  { value: 'Consultor', type: String },
  { value: 'Editor', type: String },
]

const form = reactive({
  nombre: '',
  apellidos: '',
  username: '',
  email: '',
  password: '',
  telefono: null,
  direccion: null,
  rol: '',
})

const submit = () => {
  router.post('/usuarios', form)
}
</script>

<template>
  <div>
    <Head title="Añadir Usuario" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/usuarios">Usuarios</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Añadir
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="submit">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre" />
          <TextInput v-model="form.apellidos" class="pb-8 pr-6 w-full lg:w-1/2" label="Apellidos" />
          <TextInput v-model="form.username" class="pb-8 pr-6 w-full lg:w-1/2" label="Usuario" />
          <TextInput v-model="form.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Correo electrónico" />
          <TextInput v-model="form.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Contraseña" />
          <TextInput v-model="form.telefono" class="pb-8 pr-6 w-full lg:w-1/2" label="Teléfono" />
          <TextInput v-model="form.direccion" class="pb-8 pr-6 w-full lg:w-1/2" label="Dirección" />
          <SelectInput v-model="form.rol" class="pb-8 pr-6 w-full lg:w-1/2" label="Rol">
            <option value="">Por favor seleccione</option>
            <option v-for="rol in roles" :key="rol.value" :value="rol.value">{{ rol.value }}</option>
          </SelectInput>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <LoadingButton :loading="form.processing" class="btn-yellow" type="submit">Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
