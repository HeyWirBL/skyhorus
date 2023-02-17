<script setup>
import { computed, reactive, ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import Checkbox from '@/Components/Checkbox.vue'
import Icon from '@/Components/Icon.vue'
import Logo from '@/Shared/Logo.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'

defineProps({
  status: String,
})

const imageUrl = ref('https://documental.dadsite.com.mx/background/background-hd.png')
const hidePassword = ref(true)

const form = useForm({
  usuario: '',
  password: '',
  remember: false,
})

const backgroundImage = reactive({
  backgroundImage: 'url(' + imageUrl.value + ')',
  backgroundSize: 'cover',
})

const passwordIconName = computed(() => (hidePassword.value ? 'eye' : 'eye-slash'))

const passwordFieldType = computed(() => (hidePassword.value ? 'password' : 'text'))

const login = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Inicio de Sesión" />
  <div class="flex items-center justify-center p-6 min-h-screen" :style="backgroundImage">
    <div class="w-full max-w-md">
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
        <div class="px-10 py-12">
          <Logo class="block mx-auto w-full fill-white" style="height: 200px; max-width: 15rem" />
          <div class="mt-0.5 mx-auto w-24 border-b" />
          <TextInput v-model="form.usuario" :error="form.errors.usuario" class="mt-10" label="Nombre de usuario" autofocus autocapitalize="off" />
          <div class="relative mt-6">
            <TextInput v-model="form.password" :error="form.errors.password" :type="passwordFieldType" label="Contraseña" />
            <button type="button" class="flex items-center absolute right-0 inset-y-1 z-30 mt-6 mr-2" @click="hidePassword = !hidePassword">
              <Icon class="w-5 h-5 fill-zinc-500" :name="passwordIconName" />
            </button>
          </div>
          <label class="flex items-center mt-6 select-none" for="remember">
            <Checkbox id="remember" v-model:checked="form.remember" name="remember" />
            <span class="ml-2 text-sm text-gray-600">Recuerdáme</span>
          </label>
        </div>
        <div class="flex px-10 py-4 bg-gray-100 border-t border-gray-100">
          <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Iniciar sesión</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
