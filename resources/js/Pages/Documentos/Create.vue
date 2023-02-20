<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import DropZone from '@/Components/DropZone.vue'
import SelectInput from '@/Components/SelectInput.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import { ref } from 'vue';
import debounce from 'lodash/debounce';

defineProps({
  directorios: Array,
  anos: Array,
  meses: Array,
})

const files = [];
const folder = ref('');
const year = ref('');
const month = ref('');

function showFiles(){
  console.log('hola mundo');
}


function store() {
  for (var i = 0; i < files.value.length; i++) {
    var documento = [];
    documento[i] = JSON.stringify(files.value[i]);
    const form = useForm({
      documento: documento[i],
      directorio_id: folder.value,
      ano_id: year.value,
      mes_id: month.value,
    });

    form.post('/documentos');

  }
}
</script>

<template>
  <div>
    <Head title="Subida de Documentos" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/documentos">Documentos</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Subir
    </h1>
    <div class="mb-4">
      <p class="text-sm text-gray-900">
        <span class="font-semibold">Tipos de Archivo:</span>
        <span>&nbsp;Todos los Archivos</span>
      </p>
    </div>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!-- DropZone -->
          <DropZone class="pb-8 pr-6 w-full" @files="(fl) => files.value = fl"/>
          <SelectInput class="pb-8 pr-6 w-full" label="Carpeta" v-model="folder">
            <option :value="null" />
            <option v-for="directorio in directorios" :key="directorio.id" :value="directorio.id">{{ directorio.nombre_dir }}</option>
          </SelectInput>
          <SelectInput class="pb-8 pr-6 w-full lg:w-1/2" label="Año" v-model="year">
            <option :value="null" />
            <option v-for="ano in anos" :key="ano.id" :value="ano.id">{{ ano.ano }}</option>
          </SelectInput>
          <SelectInput class="pb-8 pr-6 w-full lg:w-1/2" label="Mes" v-model="month">
            <option :value="null" />
            <option v-for="mes in meses" :key="mes.id" :value="mes.id">{{ mes.nombre }}</option>
          </SelectInput>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <LoadingButton class="btn-yellow" type="submit">Guardar</LoadingButton>
        </div>
      </form>
      <button @click="showFiles">Mostrar</button>
    </div>
  </div>
</template>
