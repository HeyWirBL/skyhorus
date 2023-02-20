<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put('/password', {
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value.focus()
      }

      if (form.errors.current_password) {
        form.reset('current_password')
        currentPasswordInput.value.focus()
      }
    },
  })
}
</script>

<template>
  <div>
    <h2 class="text-lg font-bold px-8 pt-8 pb-2 -mb-8">Actualizar Contraseña</h2>
    <p class="text-sm text-gray-600 px-8 py-8 -mb-8">Asegúrate de que tu cuenta utiliza una contraseña larga y aleatoria para mantener la seguridad.</p>
    <form @submit.prevent="updatePassword">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <TextInput ref="currentPasswordInput" v-model="form.current_password" :error="form.errors.current_password" class="pb-8 pr-6 w-full" type="password" label="Contraseña Actual" autocomplete="current-password" />
        <TextInput ref="passwordInput" v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full" type="password" label="Nueva Contraseña" autocomplete="new-password" />
        <TextInput v-model="form.password_confirmation" :error="form.errors.password_confirmation" class="pb-8 pr-6 w-full" type="password" label="Confirmar Contraseña" autocomplete="new-password" />
      </div>
      <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
        <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Guardar</LoadingButton>
      </div>
    </form>
  </div>
</template>
