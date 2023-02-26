<script setup>
import { computed, ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import CopyPasteTextInput from './Partials/CopyPasteTextInput.vue'
import FileUpload from './Partials/FileUpload.vue'

defineProps({
  pozos: Array,
})

const file = ref(null)
const textarea = ref(null)
const showTextarea = ref(false)
const showTable = ref(false)
const clickedButton = ref(null)

const showHint = computed(() => {
  return !file.value
})

const showTextAreaHandler = () => {
  showTextarea.value = true
  clickedButton.value = 'textarea'
}

const handleContinueClick = () => {
  const data = textarea.value.split(',')

  showTable.value = true
  showTextarea.value = false
  file.value = null
}

const resetForm = () => {
  file.value = null
  showTextarea.value = false
  textarea.value = null
  showTable.value = false
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
        <span>&nbsp;Importe un fichero separado por comas (.csv), Excel (.xlsx) o elija otra opción.</span>
      </p>
    </div>
    <div v-if="!showTable" class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <FileUpload v-if="!showTextarea" v-model="file" class="pb-8 pr-6 w-full" accept=".xlsx,.xls,.csv" label="Elige un archivo para importar" />
        <div v-if="!showTextarea && showHint && clickedButton !== 'file'" class="pb-8 pr-6 w-full">
          <label class="form-label">O copiar y pegar texto:</label>
          <button class="btn-secondary" type="button" @click="showTextAreaHandler">Copiar y pegar texto</button>
        </div>
        <CopyPasteTextInput v-if="showTextarea && clickedButton === 'textarea'" v-model="textarea" class="pb-8 pr-6 w-full" label="Copiar y pegar texto" />
      </div>
      <div v-if="showTextarea || file" class="flex items-center justify-start px-8 py-4 bg-gray-50 border-t border-gray-100">
        <button class="btn-yellow mr-2" type="button" @click="handleContinueClick">Continuar</button>
        <button class="btn-secondary" type="button" @click="resetForm">Regresar</button>
      </div>
    </div>
    <!-- v-if="showTable" -->
    <div class="w-full bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <th style="width: 272px; min-width: 272px; max-width: 272px">
            <select class="form-select">
              <option value="" />
            </select>
          </th>
        </thead>
      </table>
    </div>
  </div>
</template>
