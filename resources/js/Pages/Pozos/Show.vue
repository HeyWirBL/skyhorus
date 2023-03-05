<script setup>
import { nextTick, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import PozoInfo from './Partials/PozoInfo.vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const editPozo = ref(false)
const editInputRef = ref(null)

/* Form for editing a Pozo */
const editPozoForm = useForm({
  _method: 'put',
  punto_muestreo: props.pozo.punto_muestreo,
  fecha_hora: props.pozo.fecha_hora,
  identificador: props.pozo.identificador,
  presion_kgcm2: props.pozo.presion_kgcm2,
  presion_psi: props.pozo.presion_psi,
  temp_C: props.pozo.temp_C,
  temp_F: props.pozo.temp_F,
  volumen_cm3: props.pozo.volumen_cm3,
  volumen_lts: props.pozo.volumen_lts,
  observaciones: props.pozo.observaciones,
  nombre_pozo: props.pozo.nombre_pozo,
})

const openModalEditForm = () => {
  editPozo.value = true

  nextTick(() => editInputRef.value.focus())
}

const closeModalEditForm = () => {
  editPozo.value = false
  editPozoForm.reset()
}

const update = () => {
  editPozoForm.post(`/pozos/${props.pozo.id}`, {
    preserveScroll: true,
    onSuccess: () => (editPozo.value = false),
    onFinish: () => {
      if (!editPozoForm.hasErrors) {
        editPozoForm.reset()
      }
    },
  })
}
</script>

<template>
  <div>
    <Head :title="`${pozo.nombre_pozo}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ pozo.nombre_pozo }}
      </h1>
      <button class="btn-yellow ml-auto" type="button" @click="openModalEditForm">Editar Pozo</button>
    </div>

    <!-- Edit Pozo Form Modal -->
    <Modal :show="editPozo" style="max-width: 800px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Pozo [{{ pozo.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <!-- Inputs -->
        <div class="flex flex-wrap -mb-8 -mr-6 p-4">
          <TextInput ref="editInputRef" v-model="editPozoForm.nombre_pozo" :error="editPozoForm.errors.nombre_pozo" class="pb-4 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
          <TextInput v-model="editPozoForm.temp_C" :error="editPozoForm.errors.temp_C" class="pb-4 pr-6 w-full lg:w-1/2" label="°C" />
          <TextInput v-model="editPozoForm.punto_muestreo" :error="editPozoForm.errors.punto_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
          <TextInput v-model="editPozoForm.temp_F" :error="editPozoForm.errors.temp_F" class="pb-4 pr-6 w-full lg:w-1/2" label="°F" />
          <TextInput v-model="editPozoForm.fecha_hora" :error="editPozoForm.errors.fecha_hora" class="pb-4 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
          <TextInput v-model="editPozoForm.volumen_cm3" :error="editPozoForm.errors.volumen_cm3" class="pb-4 pr-6 w-full lg:w-1/2" label="CM3" />
          <TextInput v-model="editPozoForm.identificador" :error="editPozoForm.errors.identificador" class="pb-4 pr-6 w-full lg:w-1/2" label="Identificador" />
          <TextInput v-model="editPozoForm.volumen_lts" :error="editPozoForm.errors.volumen_lts" class="pb-4 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
          <TextInput v-model="editPozoForm.presion_psi" :error="editPozoForm.errors.presion_psi" class="pb-4 pr-6 w-full lg:w-1/2" label="PSIA" />
          <TextInput v-model="editPozoForm.presion_kgcm2" :error="editPozoForm.errors.presion_kgcm2" class="pb-4 pr-6 w-full lg:w-1/2" label="KG/CM2" />
          <TextareaInput v-model="editPozoForm.observaciones" :error="editPozoForm.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editPozoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditForm">Cancelar</button>
        </div>
      </form>
    </Modal>

    <!-- Pozo Table Component -->
    <PozoInfo :can="can" :pozo="pozo" />
  </div>
</template>
