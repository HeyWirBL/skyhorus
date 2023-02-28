<script setup>
import { nextTick, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const form = useForm({
  password: '',
})

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true

  nextTick(() => passwordInput.value.focus())
}

const deleteUser = () => {
  form.delete('/perfil', {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  })
}

const closeModal = () => {
  confirmingUserDeletion.value = false
  form.reset()
}
</script>

<template>
  <div>
    <h2 class="text-lg font-bold px-8 pt-8 pb-2 -mb-8">Eliminar Cuenta</h2>
    <p class="text-sm text-gray-600 px-8 py-8 -mb-8">Una vez eliminada su cuenta, no tendrá acceso al sistema, sin embargo si en un futuro necesita volver a activarse en el sistema contacte a un administrador.</p>
    <div class="flex flex-wrap -mr-6 p-8">
      <button class="btn-danger" type="button" @click="confirmUserDeletion">Eliminar Cuenta</button>
    </div>

    <Modal :show="confirmingUserDeletion" @close="closeModal">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">¿Estás seguro de querer eliminar tu cuenta?</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModal">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <div class="p-6 space-y-6">
        <p class="leading-relaxed">Una vez eliminada su cuenta, será redirigido al login de autenticación. Por favor, introduzca su contraseña para confirmar que desea eliminar definitivamente su cuenta.</p>
        <div class="mt-6">
          <TextInput ref="passwordInput" v-model="form.password" :error="form.errors.password" class="w-full md:w-3/4" type="password" label="Contraseña" />
        </div>
      </div>
      <!-- Modal footer -->
      <div class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200">
        <button class="btn-secondary" @click="closeModal">Cancelar</button>
        <LoadingButton class="btn-danger ml-3" :class="{ 'opacity-25': form.processing }" :loading="form.processing" :disabled="form.processing" @click="deleteUser">Eliminar Cuenta</LoadingButton>
      </div>
    </Modal>
  </div>
</template>
