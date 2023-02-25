<script setup>
import { reactive } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import FileUpload from '@/Components/FileUpload.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

defineProps({
  pozos: Array,
})

const form = useForm({})
const state = reactive({
  file: null,
})

const importExcel = async () => {
  let formData = new FormData()
  formData.append('file', state.file)

  const response = form.post('/componente-pozos', formData)

  console.log(response)
}

const store = () => {
  form.post('/componente-pozos')
}
</script>

<template>
  <div>
    <Head title="Importar Componentes de Pozo" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes de Pozo</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Importar
    </h1>
    <div class="mb-4">
      <p class="text-sm text-gray-900">
        <span class="font-semibold">Sugerencia:</span>
        <span>&nbsp;Arrastre un fichero separado por comas (.csv), Excel (.xlsx) o elija otra opción.</span>
      </p>
    </div>
    <div class="w-full bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="importExcel">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <FileUpload class="pb-8 pr-6 w-full lg:w-1/2" type="file" label="Elegir archivo" accept=".xlsx, .xls, .csv" />
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label>Copiar y pegar texto</label>
            <TextareaInput />
          </div>
          <!-- DropZone -->
        </div>
        <button type="submit">Importar</button>
      </form>
    </div>
  </div>
</template>
