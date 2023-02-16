<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'

const form = useForm({
  ano: '',
})

const store = () => form.post('/anos')
</script>

<template>
  <div>
    <Head title="Crear Año" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/anos">Años</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Crear
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.ano" :error="form.errors.ano" class="pb-8 pr-6 w-full" label="Año" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <LoadingButton :loading="form.processing" class="btn-yellow" type="submit">Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
