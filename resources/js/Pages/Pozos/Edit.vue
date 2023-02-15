<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  pozo: Object,
})

const form = useForm({
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

const update = () => {
  form.post(`/pozos/${props.pozo.id}`)
}

const destroy = () => {
  if (confirm('¿Estás seguro de querer eliminar este pozo?')) {
    form.delete(`/pozos/${props.pozo.id}`)
  }
}

const restore = () => {
  if (confirm('¿Estás seguro de querer restablecer este pozo?')) {
    form.put(`/pozos/${props.pozo.id}/restore`)
  }
}
</script>

<template>
  <div>
    <Head :title="`${form.nombre_pozo}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ form.nombre_pozo }}
    </h1>
    <TrashedMessage v-if="props.pozo.deleted_at" class="mb-6" @restore="restore">Este pozo ha sido eliminado.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre_pozo" :error="form.errors.nombre_pozo" class="pb-8 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
          <TextInput v-model="form.temp_C" :error="form.errors.temp_C" class="pb-8 pr-6 w-full lg:w-1/2" label="°C" />
          <TextInput v-model="form.punto_muestreo" :error="form.errors.punto_muestreo" class="pb-8 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
          <TextInput v-model="form.temp_F" :error="form.errors.temp_F" class="pb-8 pr-6 w-full lg:w-1/2" label="°F" />
          <TextInput v-model="form.fecha_hora" :error="form.errors.fecha_hora" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
          <TextInput v-model="form.volumen_cm3" :error="form.errors.volumen_cm3" class="pb-8 pr-6 w-full lg:w-1/2" label="CM3" />
          <TextInput v-model="form.identificador" :error="form.errors.identificador" class="pb-8 pr-6 w-full lg:w-1/2" label="Identificador" />
          <TextInput v-model="form.volumen_lts" :error="form.errors.volumen_lts" class="pb-8 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
          <TextInput v-model="form.presion_kgcm2" :error="form.errors.presion_kgcm2" class="pb-8 pr-6 w-full lg:w-1/2" label="KG/CM2" />
          <TextInput v-model="form.presion_psi" :error="form.errors.presion_psi" class="pb-8 pr-6 w-full lg:w-1/2" label="PSIA" />
          <TextareaInput v-model="form.observaciones" :error="form.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!props.pozo.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Pozo</button>
          <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
