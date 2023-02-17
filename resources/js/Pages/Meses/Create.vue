<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import SelectInput from '@/Components/SelectInput.vue'

defineProps({
  meses: Array,
})

const form = useForm({
  mes: '',
  nombre: '',
})

const store = () => form.post('/meses')
</script>

<template>
  <div>
    <Head title="Crear Mes" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/meses">Meses</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Crear
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <input v-model="form.mes" type="hidden" />
          <div v-if="error" class="form-error">{{ error }}</div>
          <SelectInput v-model="form.nombre" :error="form.errors.nombre" class="pb-8 pr-6 w-full" label="Mes">
            <option value="">Por favor seleccione</option>
            <option v-for="mes in meses" :key="mes.id" :value="mes.mes">{{ mes.nombre }}</option>
          </SelectInput>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <LoadingButton :loading="form.processing" :disabled="form.processing" class="btn-yellow" type="submit"> Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
