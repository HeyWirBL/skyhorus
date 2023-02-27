<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  pozo: Object,
})

const emit = defineEmits(['close'])

const editPozoModal = ref(false)

const pozoForm = useForm({
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

const updatePozo = () => {
  pozoForm.post(`/pozos/${props.pozo.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      emit('close')
    },
    onFinish: () => {
      if (!pozoForm.hasErrors) {
        pozoForm.reset()
      }
    },
  })
}

const closeModal = () => {
  emit('close')
  pozoForm.reset()
}
</script>

<template>
  <Modal :show="editPozoModal" style="max-width: 900px">
    <div class="relative">
      <!-- Modal Header -->
      <div class="flex items-start justify-between p-4 border-b rounded-t">
        <h2 class="text-xl font-semibold">Pozo, Editar [{{ pozo.id }}]</h2>

        <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModal">
          <Icon class="w-4 h-4" name="close" aria-hidden="true" />
          <span class="sr-only">Cerrar modal</span>
        </button>
      </div>

      <form @submit.prevent="updatePozo">
        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 500px; overflow: auto">
          <div class="flex flex-wrap text-sm leading-relaxed">
            <TextInput v-model="pozoForm.nombre_pozo" :error="pozoForm.errors.nombre_pozo" class="pb-4 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
            <TextInput v-model="pozoForm.temp_C" :error="pozoForm.errors.temp_C" class="pb-4 pr-6 w-full lg:w-1/2" label="°C" />
            <TextInput v-model="pozoForm.punto_muestreo" :error="pozoForm.errors.punto_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
            <TextInput v-model="pozoForm.temp_F" :error="pozoForm.errors.temp_F" class="pb-4 pr-6 w-full lg:w-1/2" label="°F" />
            <TextInput v-model="pozoForm.fecha_hora" :error="pozoForm.errors.fecha_hora" class="pb-4 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
            <TextInput v-model="pozoForm.volumen_cm3" :error="pozoForm.errors.volumen_cm3" class="pb-4 pr-6 w-full lg:w-1/2" label="CM3" />
            <TextInput v-model="pozoForm.identificador" :error="pozoForm.errors.identificador" class="pb-4 pr-6 w-full lg:w-1/2" label="Identificador" />
            <TextInput v-model="pozoForm.volumen_lts" :error="pozoForm.errors.volumen_lts" class="pb-4 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
            <TextInput v-model="pozoForm.presion_kgcm2" :error="pozoForm.errors.presion_kgcm2" class="pb-4 pr-6 w-full lg:w-1/2" label="KG/CM2" />
            <TextInput v-model="pozoForm.presion_psi" :error="pozoForm.errors.presion_psi" class="pb-4 pr-6 w-full lg:w-1/2" label="PSIA" />
            <TextareaInput v-model="pozoForm.observaciones" :error="pozoForm.errors.observaciones" class="pb-4 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
          </div>
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModal">Cancelar</button>
          <LoadingButton :loading="pozoForm.processing" class="btn-yellow ml-3" type="submit">Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </Modal>
</template>
