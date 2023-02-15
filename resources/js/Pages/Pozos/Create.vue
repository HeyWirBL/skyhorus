<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

const form = useForm({
  punto_muestreo: '',
  fecha_hora: '',
  identificador: '',
  presion_kgcm2: '',
  presion_psi: '',
  temp_C: '',
  temp_F: '',
  volumen_cm3: '',
  volumen_lts: '',
  observaciones: null,
  nombre_pozo: '',
})

const store = () => form.post('/pozos')
</script>

<template>
  <div>
    <Head title="Crear Pozo" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Crear
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <LoadingButton :loading="form.processing" class="btn-yellow" type="submit">Guardar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
