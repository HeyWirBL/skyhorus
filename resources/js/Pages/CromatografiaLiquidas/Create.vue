<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'

defineProps({
  pozos: Array,
})

var files = ref([])
const form = useForm({
  files: [],
  pozo: '',
  fecha: '',
})

function store() {
  for (let i = 0; i < files.value.length; i++) {
    form.files[i] = files.value[i]
  }
  form.post('/cromatografia-liquidas', {
    forceFormData: true,
  })
}
</script>

<template>
  <div>
    <Head title="Subida de Documentos" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/cromatografia-liquidas">Cromatografía Líquida</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Subir
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <input type="file" accept="application/pdf" multiple @input="files = $event.target.files" />
        </div>
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <SelectInput v-model="form.pozo" class="pb-8 pr-6 w-full lg:w-1/2" label="Pozo">
            <option value="">Por favor seleccione</option>
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
          <TextInput v-model="form.fecha" class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <Link class="btn-secondary" href="/cromatografia-liquidas" as="button">Cancelar</Link>
          <LoadingButton class="btn-yellow ml-auto" type="submit">Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
