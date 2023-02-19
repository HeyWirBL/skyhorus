<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import SelectInput from '@/Components/SelectInput.vue'

const props = defineProps({
  componentePozo: Object,
  pozos: Array,
})

const swal = inject('$swal')

const form = useForm({
  _method: 'put',
  dioxido_carbono: props.componentePozo.dioxido_carbono,
  pe_dioxido_carbono: props.componentePozo.pe_dioxido_carbono,
  mo_dioxido_carbono: props.componentePozo.mo_dioxido_carbono,
  den_dioxido_carbono: props.componentePozo.den_dioxido_carbono,
  acido_sulfidrico: props.componentePozo.acido_sulfidrico,
  pe_acido_sulfidrico: props.componentePozo.pe_acido_sulfidrico,
  mo_acido_sulfidrico: props.componentePozo.mo_acido_sulfidrico,
  den_acido_sulfidrico: props.componentePozo.den_acido_sulfidrico,
  nitrogeno: props.componentePozo.nitrogeno,
  pe_nitrogeno: props.componentePozo.pe_nitrogeno,
  mo_nitrogeno: props.componentePozo.mo_nitrogeno,
  den_nitrogeno: props.componentePozo.den_nitrogeno,
  metano: props.componentePozo.metano,
  pe_metano: props.componentePozo.pe_metano,
  mo_metano: props.componentePozo.mo_metano,
  den_metano: props.componentePozo.den_metano,
  etano: props.componentePozo.etano,
  pe_etano: props.componentePozo.pe_etano,
  mo_etano: props.componentePozo.mo_etano,
  den_etano: props.componentePozo.den_etano,
  propano: props.componentePozo.propano,
  pe_propano: props.componentePozo.pe_propano,
  mo_propano: props.componentePozo.mo_propano,
  den_propano: props.componentePozo.den_propano,
  iso_butano: props.componentePozo.iso_butano,
  pe_iso_butano: props.componentePozo.pe_iso_butano,
  mo_iso_butano: props.componentePozo.mo_iso_butano,
  den_iso_butano: props.componentePozo.den_iso_butano,
  n_butano: props.componentePozo.n_butano,
  pe_n_butano: props.componentePozo.pe_n_butano,
  mo_n_butano: props.componentePozo.mo_n_butano,
  den_n_butano: props.componentePozo.den_n_butano,
  iso_pentano: props.componentePozo.iso_pentano,
  pe_iso_pentano: props.componentePozo.pe_iso_pentano,
  mo_iso_pentano: props.componentePozo.mo_iso_pentano,
  den_iso_pentano: props.componentePozo.den_iso_pentano,
  n_pentano: props.componentePozo.n_pentano,
  pe_n_pentano: props.componentePozo.pe_n_pentano,
  mo_n_pentano: props.componentePozo.mo_n_pentano,
  den_n_pentano: props.componentePozo.den_n_pentano,
  n_exano: props.componentePozo.n_exano,
  pe_n_exano: props.componentePozo.pe_n_exano,
  mo_n_exano: props.componentePozo.mo_n_exano,
  den_n_exano: props.componentePozo.den_n_exano,
  fecha_recep: props.componentePozo.fecha_recep,
  fecha_analisis: props.componentePozo.fecha_analisis,
  no_determinacion: props.componentePozo.no_determinacion,
  equipo_utilizado: props.componentePozo.equipo_utilizado,
  met_laboratorio: props.componentePozo.met_laboratorio,
  observaciones: props.componentePozo.observaciones,
  nombre_componente: props.componentePozo.nombre_componente,
  pozo_id: props.componentePozo.pozo_id,
  fecha_muestreo: props.componentePozo.fecha_muestreo,
})

const update = () => {
  form.post(`/componente-pozos/${props.componentePozo.id}`)
}

const destroy = () => {
  swal({
    title: '¿Estás seguro de querer eliminar estos componentes de pozo?',
    text: 'Al hacer clic en el botón de confirmar se enviarán al modo "Solo Eliminado".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.delete(`/componente-pozos/${props.componentePozo.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer estos componentes de pozo?',
    text: 'Se restablecerán del modo "Solo Eliminado" y pasarán al estado "Con Modificación".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/componente-pozos/${props.componentePozo.id}/restore`)
    }
  })
}
</script>

<template>
  <div>
    <Head :title="`${form.nombre_componente}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes del Pozo</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span>
      <Link class="text-yellow-400 hover:text-yellow-600" :href="`/componente-pozos/${componentePozo.id}`">&nbsp;Detalles</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ form.nombre_componente }}
    </h1>
    <TrashedMessage v-if="componentePozo.deleted_at" class="mb-6" @restore="restore">Estos componentes del pozo han sido eliminados.</TrashedMessage>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <TextInput v-model="form.nombre_componente" :error="form.errors.nombre_componente" class="pb-8 pr-6 w-full" label="Nombre del grupo de componentes" />
          <SelectInput v-model="form.pozo_id" :error="form.errors.pozo_id" class="pb-8 pr-6 w-full" label="Pozo/Instalación">
            <option :value="null" />
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
          <!-- Dioxido de Carbono -->
          <TextInput v-model="form.dioxido_carbono" :error="form.errors.dioxido_carbono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dióxido de carbono - PM" />
          <TextInput v-model="form.pe_dioxido_carbono" :error="form.errors.pe_dioxido_carbono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dióxido de carbono - % Peso" />
          <TextInput v-model="form.mo_dioxido_carbono" :error="form.errors.mo_dioxido_carbono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dióxido de carbono - % MOL" />
          <TextInput v-model="form.den_dioxido_carbono" :error="form.errors.den_dioxido_carbono" class="pb-8 pr-6 w-full lg:w-1/2" label="Dióxido de carbono - Densidad" />
          <!-- Ácido Sulfidrico -->
          <TextInput v-model="form.acido_sulfidrico" :error="form.errors.acido_sulfidrico" class="pb-8 pr-6 w-full lg:w-1/2" label="Ácido Sulfhídrico - PM" />
          <TextInput v-model="form.pe_acido_sulfidrico" :error="form.errors.pe_acido_sulfidrico" class="pb-8 pr-6 w-full lg:w-1/2" label="Ácido Sulfhídrico - % Peso" />
          <TextInput v-model="form.mo_acido_sulfidrico" :error="form.errors.mo_acido_sulfidrico" class="pb-8 pr-6 w-full lg:w-1/2" label="Ácido Sulfhídrico - % MOL" />
          <TextInput v-model="form.den_acido_sulfidrico" :error="form.errors.den_acido_sulfidrico" class="pb-8 pr-6 w-full lg:w-1/2" label="Ácido Sulfhídrico - Densidad" />
          <!-- Nitrógeno -->
          <TextInput v-model="form.nitrogeno" :error="form.errors.nitrogeno" class="pb-8 pr-6 w-full lg:w-1/2" label="Nitrógeno - PM" />
          <TextInput v-model="form.pe_nitrogeno" :error="form.errors.pe_nitrogeno" class="pb-8 pr-6 w-full lg:w-1/2" label="Nitrógeno - % Peso" />
          <TextInput v-model="form.mo_nitrogeno" :error="form.errors.mo_nitrogeno" class="pb-8 pr-6 w-full lg:w-1/2" label="Nitrógeno - % MOL" />
          <TextInput v-model="form.den_nitrogeno" :error="form.errors.den_nitrogeno" class="pb-8 pr-6 w-full lg:w-1/2" label="Nitrógeno - Densidad" />
          <!-- Metano -->
          <TextInput v-model="form.metano" :error="form.errors.metano" class="pb-8 pr-6 w-full lg:w-1/2" label="Metano - PM" />
          <TextInput v-model="form.pe_metano" :error="form.errors.pe_metano" class="pb-8 pr-6 w-full lg:w-1/2" label="Metano - % Peso" />
          <TextInput v-model="form.mo_metano" :error="form.errors.mo_metano" class="pb-8 pr-6 w-full lg:w-1/2" label="Metano - % MOL" />
          <TextInput v-model="form.den_metano" :error="form.errors.den_metano" class="pb-8 pr-6 w-full lg:w-1/2" label="Metano - Densidad" />
          <!-- Etano -->
          <TextInput v-model="form.etano" :error="form.errors.etano" class="pb-8 pr-6 w-full lg:w-1/2" label="Etano - PM" />
          <TextInput v-model="form.pe_etano" :error="form.errors.pe_etano" class="pb-8 pr-6 w-full lg:w-1/2" label="Etano - % Peso" />
          <TextInput v-model="form.mo_etano" :error="form.errors.mo_etano" class="pb-8 pr-6 w-full lg:w-1/2" label="Etano - % MOL" />
          <TextInput v-model="form.den_etano" :error="form.errors.den_etano" class="pb-8 pr-6 w-full lg:w-1/2" label="Etano - Densidad" />
          <!-- Propano -->
          <TextInput v-model="form.propano" :error="form.errors.propano" class="pb-8 pr-6 w-full lg:w-1/2" label="Propano - PM" />
          <TextInput v-model="form.pe_propano" :error="form.errors.pe_propano" class="pb-8 pr-6 w-full lg:w-1/2" label="Propano - % Peso" />
          <TextInput v-model="form.mo_propano" :error="form.errors.mo_propano" class="pb-8 pr-6 w-full lg:w-1/2" label="Propano - % MOL" />
          <TextInput v-model="form.den_propano" :error="form.errors.den_propano" class="pb-8 pr-6 w-full lg:w-1/2" label="Propano - Densidad" />
          <!-- Isobutano -->
          <TextInput v-model="form.iso_butano" :error="form.errors.iso_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isobutano - PM" />
          <TextInput v-model="form.pe_iso_butano" :error="form.errors.pe_iso_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isobutano - % Peso" />
          <TextInput v-model="form.mo_iso_butano" :error="form.errors.mo_iso_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isobutano - % MOL" />
          <TextInput v-model="form.den_iso_butano" :error="form.errors.den_iso_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isobutano - Densidad" />
          <!-- Butano -->
          <TextInput v-model="form.n_butano" :error="form.errors.n_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Butano - PM" />
          <TextInput v-model="form.pe_n_butano" :error="form.errors.pe_n_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Butano - % Peso" />
          <TextInput v-model="form.mo_n_butano" :error="form.errors.mo_n_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Butano - % MOL" />
          <TextInput v-model="form.den_n_butano" :error="form.errors.den_n_butano" class="pb-8 pr-6 w-full lg:w-1/2" label="Butano - Densidad" />
          <!-- Isopentano -->
          <TextInput v-model="form.iso_pentano" :error="form.errors.iso_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isopentano - PM" />
          <TextInput v-model="form.pe_iso_pentano" :error="form.errors.pe_iso_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isopentano - % Peso" />
          <TextInput v-model="form.mo_iso_pentano" :error="form.errors.mo_iso_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isopentano - % MOL" />
          <TextInput v-model="form.den_iso_pentano" :error="form.errors.den_iso_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Isopentano - Densidad" />
          <!-- Pentano -->
          <TextInput v-model="form.n_pentano" :error="form.errors.n_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Pentano - PM" />
          <TextInput v-model="form.pe_n_pentano" :error="form.errors.pe_n_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Pentano - % Peso" />
          <TextInput v-model="form.mo_n_pentano" :error="form.errors.mo_n_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Pentano - % MOL" />
          <TextInput v-model="form.den_n_pentano" :error="form.errors.den_n_pentano" class="pb-8 pr-6 w-full lg:w-1/2" label="Pentano - Densidad" />
          <!-- Hexano -->
          <TextInput v-model="form.n_exano" :error="form.errors.n_exano" class="pb-8 pr-6 w-full lg:w-1/2" label="Hexano - PM" />
          <TextInput v-model="form.pe_n_exano" :error="form.errors.pe_n_exano" class="pb-8 pr-6 w-full lg:w-1/2" label="Hexano - % Peso" />
          <TextInput v-model="form.mo_n_exano" :error="form.errors.mo_n_exano" class="pb-8 pr-6 w-full lg:w-1/2" label="Hexano - % MOL" />
          <TextInput v-model="form.den_n_exano" :error="form.errors.den_n_exano" class="pb-8 pr-6 w-full lg:w-1/2" label="Hexano - Densidad" />
          <!-- Additional Fields -->
          <TextInput v-model="form.fecha_recep" :error="form.errors.fecha_recep" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Recepción" type="date" />
          <TextInput v-model="form.fecha_analisis" :error="form.errors.fecha_analisis" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Análisis" type="date" />
          <TextInput v-model="form.fecha_muestreo" :error="form.errors.fecha_muestreo" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Muestreo" type="date" />
          <TextInput v-model="form.no_determinacion" :error="form.errors.no_determinacion" class="pb-8 pr-6 w-full lg:w-1/2" label="No. Determinación" />
          <TextInput v-model="form.equipo_utilizado" :error="form.errors.equipo_utilizado" class="pb-8 pr-6 w-full lg:w-1/2" label="Equipo utilizado" />
          <TextInput v-model="form.met_laboratorio" :error="form.errors.met_laboratorio" class="pb-8 pr-6 w-full lg:w-1/2" label="Método del laboratorio" />
          <TextareaInput v-model="form.observaciones" :error="form.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!componentePozo.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">
            <span>Eliminar</span>
            <span class="hidden md:inline">&nbsp;Componentes del Pozo</span>
          </button>
          <LoadingButton :loading="form.processing" class="btn-yellow ml-auto" type="submit">Actualizar</LoadingButton>
        </div>
      </form>
    </div>
  </div>
</template>
