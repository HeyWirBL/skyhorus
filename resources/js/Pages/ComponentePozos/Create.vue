<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import FileUpload from '@/Components/FileUpload.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

defineProps({
  pozos: Array,
})

var file = ref([]);
const form = useForm({
  file: [],
  pozoId: '',
  fechaRecep: '',
  fechaAnalisis: '',
  fechaMuest: '',
})

function importExcel(){
  for( let i = 0; i < file.value.length; i++){
    form.file[i] = file.value[i];
  }

  const response = form.post('/componente-pozos/import', {
    forceFormData: true,
  })

  console.log(response)
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
          <!-- <FileUpload class="pb-8 pr-6 w-full lg:w-1/2" type="file" label="Elegir archivo" accept=".xlsx, .xls, .csv"  @input="file = $event.target.files"/>  -->
          <input type="file" accept=".xlsx, .xls, .csv" @input="file = $event.target.files">
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label>Copiar y pegar texto</label>
            <TextareaInput />
          </div>
          <div class="flex flex-col pb-8 pr-6">
            <label class="form-label">ID del pozo (buscar por nombre)</label>
            <input list="datalist" type="text" v-model="form.pozoId" placeholder="Buscar un pozo">
            <datalist id="datalist">
              <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
            </datalist>
          </div>
          <div class="flex flex-col pb-8 pr-6">
            <label class="form-label">Fecha de recepción</label>
            <input type="date" v-model="form.fechaRecep">
          </div>
          <div class="flex flex-col pb-8 pr-6">
            <label class="form-label">Fecha de análisis</label>
            <input type="date" v-model="form.fechaAnalisis">
          </div>
          <div class="flex flex-col pb-8 pr-6">
            <label class="form-label">Fecha de muestreo</label>
            <input type="date" v-model="form.fechaMuest">
          </div>
        </div>
        <div class="flex items-center mb-6 ml-8">
          <button class="btn-yellow" type="submit">Importar</button>
        </div>
      </form>
    </div>
  </div>
</template>
