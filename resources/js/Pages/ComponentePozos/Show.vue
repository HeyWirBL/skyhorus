<script setup>
import { computed, inject, nextTick, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import LineChart from './Partials/LineChart.vue'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  can: Object,
  componentePozo: Object,
  pozos: Array,
})

const swal = inject('$swal')

const editComponenteModal = ref(false)
const showMessageMetLab = ref(false)
const showMessageObs = ref(false)
const firstInput = ref(null)

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

const messageMetLab = computed(() => props.componentePozo.met_laboratorio)
const messageObs = computed(() => props.componentePozo.observaciones)

const openModal = () => {
  editComponenteModal.value = true

  nextTick(() => firstInput.value.focus())
}

const openModalMessageMet = () => {
  showMessageMetLab.value = true
}

const openModalObs = () => {
  showMessageObs.value = true
}

const updateComponentePozo = () => {
  form.post(`/componente-pozos/${props.componentePozo.id}`, {
    preserveScroll: true,
    onSuccess: () => (editComponenteModal.value = false),
    onError: () => firstInput.value.focus(),
    onFinish: () => {
      if (!form.hasErrors) {
        form.reset()
      }
    },
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer estos componentes de este pozo?',
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

const closeModal = () => {
  editComponenteModal.value = false
  form.reset()
}

const closeModalMessageMet = () => {
  showMessageMetLab.value = false
}

const closeModalMessageObs = () => {
  showMessageObs.value = false
}

const download = () => {
  return window.open('/componente-pozos/export/' + props.componentePozo.id, '_blank')
}

const truncateMessageMetLab = computed(() => {
  const maxLength = 30
  if (messageMetLab.value.length <= maxLength) {
    return messageMetLab.value
  }
  return messageMetLab.value.substring(0, maxLength)
})

const truncateMessageObs = computed(() => {
  const maxLength = 30
  if (messageObs.value.length <= maxLength) {
    return messageObs.value
  }
  return messageObs.value.substring(0, maxLength)
})
</script>

<template>
  <div>
    <Head :title="`${componentePozo.nombre_componente}`" />

    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes del Pozo</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ componentePozo.nombre_componente }}
      </h1>
      <button v-if="can.editComponentePozo" class="btn-yellow ml-auto" @click="openModal">
        <span>Editar</span>
        <span class="hidden md:inline">&nbsp;Componentes</span>
      </button>
    </div>

    <LineChart />

    <TrashedMessage v-if="componentePozo.deleted_at && can.restoreComponentePozo" class="mb-6" @restore="restore">Estos componentes de pozo han sido eliminados.</TrashedMessage>

    <Modal :show="editComponenteModal" style="max-width: 1000px">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Componentes Pozo, Editar [{{ componentePozo.id }}]</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModal">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 600px; overflow: auto">
          <form @submit.prevent="updateComponentePozo">
            <div class="flex flex-wrap text-sm leading-relaxed">
              <TextInput ref="firstInput" v-model="form.nombre_componente" :error="form.errors.nombre_componente" class="pb-8 pr-6 w-full lg:w-1/2" label="Nombre del grupo de componentes" />
              <SelectInput v-model="form.pozo_id" :error="form.errors.pozo_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Pozo/Instalación">
                <option :value="null" />
                <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
              </SelectInput>
              <!-- Dioxido de Carbono -->
              <TextInput v-model="form.dioxido_carbono" :error="form.errors.dioxido_carbono" class="pb-8 pr-4" label="Dióxido de carbono - PM" />
              <TextInput v-model="form.pe_dioxido_carbono" :error="form.errors.pe_dioxido_carbono" class="pb-8 pr-4" label="Dióxido de carbono - % Peso" />
              <TextInput v-model="form.mo_dioxido_carbono" :error="form.errors.mo_dioxido_carbono" class="pb-8 pr-4" label="Dióxido de carbono - % MOL" />
              <TextInput v-model="form.den_dioxido_carbono" :error="form.errors.den_dioxido_carbono" class="pb-8 pr-4" label="Dióxido de carbono - Densidad" />
              <!-- Ácido Sulfidrico -->
              <TextInput v-model="form.acido_sulfidrico" :error="form.errors.acido_sulfidrico" class="pb-8 pr-4" label="Ácido Sulfhídrico - PM" />
              <TextInput v-model="form.pe_acido_sulfidrico" :error="form.errors.pe_acido_sulfidrico" class="pb-8 pr-4" label="Ácido Sulfhídrico - % Peso" />
              <TextInput v-model="form.mo_acido_sulfidrico" :error="form.errors.mo_acido_sulfidrico" class="pb-8 pr-4" label="Ácido Sulfhídrico - % MOL" />
              <TextInput v-model="form.den_acido_sulfidrico" :error="form.errors.den_acido_sulfidrico" class="pb-8 pr-4" label="Ácido Sulfhídrico - Densidad" />
              <!-- Nitrógeno -->
              <TextInput v-model="form.nitrogeno" :error="form.errors.nitrogeno" class="pb-8 pr-4" label="Nitrógeno - PM" />
              <TextInput v-model="form.pe_nitrogeno" :error="form.errors.pe_nitrogeno" class="pb-8 pr-4" label="Nitrógeno - % Peso" />
              <TextInput v-model="form.mo_nitrogeno" :error="form.errors.mo_nitrogeno" class="pb-8 pr-4" label="Nitrógeno - % MOL" />
              <TextInput v-model="form.den_nitrogeno" :error="form.errors.den_nitrogeno" class="pb-8 pr-4" label="Nitrógeno - Densidad" />
              <!-- Metano -->
              <TextInput v-model="form.metano" :error="form.errors.metano" class="pb-8 pr-4" label="Metano - PM" />
              <TextInput v-model="form.pe_metano" :error="form.errors.pe_metano" class="pb-8 pr-4" label="Metano - % Peso" />
              <TextInput v-model="form.mo_metano" :error="form.errors.mo_metano" class="pb-8 pr-4" label="Metano - % MOL" />
              <TextInput v-model="form.den_metano" :error="form.errors.den_metano" class="pb-8 pr-4" label="Metano - Densidad" />
              <!-- Etano -->
              <TextInput v-model="form.etano" :error="form.errors.etano" class="pb-8 pr-4" label="Etano - PM" />
              <TextInput v-model="form.pe_etano" :error="form.errors.pe_etano" class="pb-8 pr-4" label="Etano - % Peso" />
              <TextInput v-model="form.mo_etano" :error="form.errors.mo_etano" class="pb-8 pr-4" label="Etano - % MOL" />
              <TextInput v-model="form.den_etano" :error="form.errors.den_etano" class="pb-8 pr-4" label="Etano - Densidad" />
              <!-- Propano -->
              <TextInput v-model="form.propano" :error="form.errors.propano" class="pb-8 pr-4" label="Propano - PM" />
              <TextInput v-model="form.pe_propano" :error="form.errors.pe_propano" class="pb-8 pr-4" label="Propano - % Peso" />
              <TextInput v-model="form.mo_propano" :error="form.errors.mo_propano" class="pb-8 pr-4" label="Propano - % MOL" />
              <TextInput v-model="form.den_propano" :error="form.errors.den_propano" class="pb-8 pr-4" label="Propano - Densidad" />
              <!-- Isobutano -->
              <TextInput v-model="form.iso_butano" :error="form.errors.iso_butano" class="pb-8 pr-4" label="Isobutano - PM" />
              <TextInput v-model="form.pe_iso_butano" :error="form.errors.pe_iso_butano" class="pb-8 pr-4" label="Isobutano - % Peso" />
              <TextInput v-model="form.mo_iso_butano" :error="form.errors.mo_iso_butano" class="pb-8 pr-4" label="Isobutano - % MOL" />
              <TextInput v-model="form.den_iso_butano" :error="form.errors.den_iso_butano" class="pb-8 pr-4" label="Isobutano - Densidad" />
              <!-- Butano -->
              <TextInput v-model="form.n_butano" :error="form.errors.n_butano" class="pb-8 pr-4" label="Butano - PM" />
              <TextInput v-model="form.pe_n_butano" :error="form.errors.pe_n_butano" class="pb-8 pr-4" label="Butano - % Peso" />
              <TextInput v-model="form.mo_n_butano" :error="form.errors.mo_n_butano" class="pb-8 pr-4" label="Butano - % MOL" />
              <TextInput v-model="form.den_n_butano" :error="form.errors.den_n_butano" class="pb-8 pr-4" label="Butano - Densidad" />
              <!-- Isopentano -->
              <TextInput v-model="form.iso_pentano" :error="form.errors.iso_pentano" class="pb-8 pr-4" label="Isopentano - PM" />
              <TextInput v-model="form.pe_iso_pentano" :error="form.errors.pe_iso_pentano" class="pb-8 pr-4" label="Isopentano - % Peso" />
              <TextInput v-model="form.mo_iso_pentano" :error="form.errors.mo_iso_pentano" class="pb-8 pr-4" label="Isopentano - % MOL" />
              <TextInput v-model="form.den_iso_pentano" :error="form.errors.den_iso_pentano" class="pb-8 pr-4" label="Isopentano - Densidad" />
              <!-- Pentano -->
              <TextInput v-model="form.n_pentano" :error="form.errors.n_pentano" class="pb-8 pr-4" label="Pentano - PM" />
              <TextInput v-model="form.pe_n_pentano" :error="form.errors.pe_n_pentano" class="pb-8 pr-4" label="Pentano - % Peso" />
              <TextInput v-model="form.mo_n_pentano" :error="form.errors.mo_n_pentano" class="pb-8 pr-4" label="Pentano - % MOL" />
              <TextInput v-model="form.den_n_pentano" :error="form.errors.den_n_pentano" class="pb-8 pr-4" label="Pentano - Densidad" />
              <!-- Hexano -->
              <TextInput v-model="form.n_exano" :error="form.errors.n_exano" class="pb-8 pr-4" label="Hexano - PM" />
              <TextInput v-model="form.pe_n_exano" :error="form.errors.pe_n_exano" class="pb-8 pr-4" label="Hexano - % Peso" />
              <TextInput v-model="form.mo_n_exano" :error="form.errors.mo_n_exano" class="pb-8 pr-4" label="Hexano - % MOL" />
              <TextInput v-model="form.den_n_exano" :error="form.errors.den_n_exano" class="pb-8 pr-4" label="Hexano - Densidad" />
              <!-- Additional Fields -->
              <TextInput v-model="form.fecha_recep" :error="form.errors.fecha_recep" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Recepción" type="date" />
              <TextInput v-model="form.fecha_analisis" :error="form.errors.fecha_analisis" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Análisis" type="date" />
              <TextInput v-model="form.fecha_muestreo" :error="form.errors.fecha_muestreo" class="pb-8 pr-6 w-full lg:w-1/2" label="Fecha de Muestreo" type="date" />
              <TextInput v-model="form.no_determinacion" :error="form.errors.no_determinacion" class="pb-8 pr-6 w-full lg:w-1/2" label="No. Determinación" />
              <TextInput v-model="form.equipo_utilizado" :error="form.errors.equipo_utilizado" class="pb-8 pr-6 w-full lg:w-1/2" label="Equipo utilizado" />
              <TextInput v-model="form.met_laboratorio" :error="form.errors.met_laboratorio" class="pb-8 pr-6 w-full lg:w-1/2" label="Método del laboratorio" />
              <TextareaInput v-model="form.observaciones" :error="form.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
            </div>

            <div class="mt-6 flex justify-end">
              <button class="btn-secondary" type="button" @click="closeModal">Cancelar</button>
              <LoadingButton :loading="form.processing" class="btn-yellow ml-3" type="submit">Guardar</LoadingButton>
            </div>
          </form>
        </div>
      </div>
    </Modal>

    <Modal :show="showMessageMetLab" @close="closeModalMessageMet">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Método de laboratorio</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalMessageMet">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 150px">
          <p class="text-base text-gray-600">{{ componentePozo.met_laboratorio }}</p>
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModalMessageMet">Cerrar</button>
        </div>
      </div>
    </Modal>

    <Modal :show="showMessageObs" @close="closeModalMessageObs">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Observaciones</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalMessageObs">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 150px">
          <p class="text-base text-gray-600">{{ componentePozo.observaciones }}</p>
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModalMessageObs">Cerrar</button>
        </div>
      </div>
    </Modal>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-white border-b-2">
          <tr>
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
          </tr>
        </thead>
        <tbody>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">{{ componentePozo.pozo.nombre_pozo }}</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="3">
              <span class="block bg-gray-400 text-white font-normal leading-6">Datos de análisis en laboratorio</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="2">
              <span class="block bg-gray-400 text-white font-normal leading-6">Nombre del grupo</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de recepción</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de análisis</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Número de determinación</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ componentePozo.nombre_componente }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.fecha_recep }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.fecha_analisis }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.no_determinacion }}</span>
            </td>
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Equipo utilizado</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Método de laboratorio</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de muestreo</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ componentePozo.fecha_muestreo }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.equipo_utilizado }}</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="2">
              <span class="block leading-6">
                {{ truncateMessageMetLab }}
                <a class="text-yellow-400 hover:underline" href="#" @click="openModalMessageMet">Ver Más...</a>
              </span>
            </td>
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Observaciones</span>
            </td>
            <td class="text-left border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ truncateMessageObs }} <a class="text-yellow-400 hover:underline" href="#" @click="openModalObs">Ver Más...</a></span>
            </td>
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4" />
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Información</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">PM</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">% Peso</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">% MOL</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Densidad</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Dióxido de carbono</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.dioxido_carbono }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_dioxido_carbono }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Ácido sulfhídrico</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.acido_sulfidrico }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_acido_sulfidrico }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Nitrógeno</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.nitrogeno }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_nitrogeno }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Metano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.metano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_metano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Etano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.etano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_etano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Propano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.propano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_propano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Iso-Butano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.iso_butano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_iso_butano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">n-Butano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.n_butano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_n_butano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Iso-Pentano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.iso_pentano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_iso_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">n-Petano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.n_pentano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_n_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">**n-Exano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.n_exano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ componentePozo.den_n_exano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Total</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">540.86</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">100.00</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">100</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">0</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-left px-6 py-4" colspan="5">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Documento PDF</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="" class="font-medium text-yellow-600 hover:text-yellow-500" type="button" @click.prevent="download">Descargar</Link>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Visualizar</Link>
                  </div>
                </li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
