<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { reactive } from 'vue'

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
    <Head title="Importar Componentes Pozo" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes de Pozo</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Importar
    </h1>
    <div class="mb-4">
      <p class="text-sm text-gray-900">
        <span class="font-semibold">Sugerencia:</span>
        <span>&nbsp;Arrastre un fichero separado por comas (.csv), Excel (.xlsx).</span>
      </p>
    </div>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="importExcel">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!-- DropZone -->
          <input id="excel-file" ref="fileInput" type="file" name="excel-file" accept=".xlsx, .xls, .csv" />
        </div>
        <button type="submit">Importar</button>
      </form>
    </div>
  </div>
</template>
