<script setup>
import { computed, inject, nextTick, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'
import chartjsPluginDatalabels from 'chartjs-plugin-datalabels'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, chartjsPluginDatalabels)

const props = defineProps({
  can: Object,
  componentePozo: Object,
  pozos: Array,
  quimicosData: Array,
})

const swal = inject('$swal')

const editComponenteModal = ref(false)
const showMessageMetLab = ref(false)
const showMessageObs = ref(false)
const showChart = ref(false)
const firstInput = ref(null)

const chartOptions = {
  plugins: {
    datalabels: {
      align: 'end',
      anchor: 'end',
      color: '#555555',
      font: {
        size: 14,
        weight: 'bold',
      },
      formatter: (value, context) => {
        return context.chart.data.labels[context.dataIndex].label
      },
    },
  },
  responsive: true,
  scales: {
    x: {
      display: true,
      title: {
        display: true,
        text: 'Componentes',
        color: '#555555',
        font: {
          size: 15,
          weight: 'bold',
          lineHeight: 1.2,
        },
      },
    },
    y: {
      display: true,
      title: {
        display: true,
        text: 'Total Mo',
        color: '#555555',
        font: {
          size: 15,
          weight: 'bold',
          lineHeight: 1.2,
        },
      },
    },
  },
}

/**
 * Computed Property: Formats the data received from the props in a format
 * that can be used by the chart. This includes mapping the `quimicosData`
 * array to generate the chart labels and data.
 */
const chartDataFormatted = computed(() => {
  return {
    labels: props.quimicosData.map((q) => q.Quimico),
    datasets: [
      {
        label: 'Total Mo',
        data: props.quimicosData.map((q) => q.Total_mo),
        backgroundColor: 'rgba(100, 181, 246, 1)',
        borderColor: 'rgba(105, 183, 246, 1)',
        borderWidth: 2,
        pointStyle: 'circle',
        pointRadius: 6,
        pointHoverRadius: 12,
      },
    ],
  }
})

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

const messageMetLab = computed(() => props.componentePozo?.met_laboratorio)
const messageObs = computed(() => props.componentePozo?.observaciones)

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

const openModalChart = () => {
  showChart.value = true
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
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
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

const closeModalChart = () => {
  showChart.value = false
}

const truncateMessageMetLab = computed(() => {
  const maxLength = 38
  const metLab = messageMetLab.value ?? ''
  return metLab.length > maxLength ? metLab.substring(0, maxLength) : metLab || 'No tiene un método de laboratorio.'
})

const truncateMessageObs = computed(() => {
  const maxLength = 40
  const observaciones = messageObs.value ?? ''
  return observaciones.length > maxLength ? observaciones.substring(0, maxLength) : observaciones || 'Aún no hay observaciones.'
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

    <TrashedMessage v-if="componentePozo.deleted_at && can.restoreComponentePozo" class="mb-6" @restore="restore">Estos componentes de pozo han sido eliminados.</TrashedMessage>

    <!-- Edit Com Pozo Form Modal -->
    <Modal :show="editComponenteModal" style="max-width: 1015px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Componentes Pozo [{{ componentePozo.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModal">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="updateComponentePozo">
        <div class="relative flex flex-wrap p-4 overflow-y-auto" style="height: 513.6px">
          <TextInput ref="firstInput" v-model="form.nombre_componente" :error="form.errors.nombre_componente" class="pb-4 pr-6 w-full lg:w-1/2" label="Nombre del grupo de componentes" />
          <SelectInput v-model="form.pozo_id" :error="form.errors.pozo_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Pozo/Instalación">
            <option :value="null" />
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
          <TextInput v-model="form.dioxido_carbono" :error="form.errors.dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - PM" />
          <TextInput v-model="form.pe_dioxido_carbono" :error="form.errors.pe_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % Peso" />
          <TextInput v-model="form.mo_dioxido_carbono" :error="form.errors.mo_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % MOL" />
          <TextInput v-model="form.den_dioxido_carbono" :error="form.errors.den_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - Densidad" />

          <TextInput v-model="form.acido_sulfidrico" :error="form.errors.acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - PM" />
          <TextInput v-model="form.pe_acido_sulfidrico" :error="form.errors.pe_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % Peso" />
          <TextInput v-model="form.mo_acido_sulfidrico" :error="form.errors.mo_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % MOL" />
          <TextInput v-model="form.den_acido_sulfidrico" :error="form.errors.den_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - Densidad" />

          <TextInput v-model="form.nitrogeno" :error="form.errors.nitrogeno" class="pb-4 pr-6" label="Nitrógeno - PM" />
          <TextInput v-model="form.pe_nitrogeno" :error="form.errors.pe_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % Peso" />
          <TextInput v-model="form.mo_nitrogeno" :error="form.errors.mo_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % MOL" />
          <TextInput v-model="form.den_nitrogeno" :error="form.errors.den_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - Densidad" />

          <TextInput v-model="form.metano" :error="form.errors.metano" class="pb-4 pr-6" label="Metano - PM" />
          <TextInput v-model="form.pe_metano" :error="form.errors.pe_metano" class="pb-4 pr-6" label="Metano - % Peso" />
          <TextInput v-model="form.mo_metano" :error="form.errors.mo_metano" class="pb-4 pr-6" label="Metano - % MOL" />
          <TextInput v-model="form.den_metano" :error="form.errors.den_metano" class="pb-4 pr-6" label="Metano - Densidad" />

          <TextInput v-model="form.etano" :error="form.errors.etano" class="pb-4 pr-6" label="Etano - PM" />
          <TextInput v-model="form.pe_etano" :error="form.errors.pe_etano" class="pb-4 pr-6" label="Etano - % Peso" />
          <TextInput v-model="form.mo_etano" :error="form.errors.mo_etano" class="pb-4 pr-6" label="Etano - % MOL" />
          <TextInput v-model="form.den_etano" :error="form.errors.den_etano" class="pb-4 pr-6" label="Etano - Densidad" />

          <TextInput v-model="form.propano" :error="form.errors.propano" class="pb-4 pr-6" label="Propano - PM" />
          <TextInput v-model="form.pe_propano" :error="form.errors.pe_propano" class="pb-4 pr-6" label="Propano - % Peso" />
          <TextInput v-model="form.mo_propano" :error="form.errors.mo_propano" class="pb-4 pr-6" label="Propano - % MOL" />
          <TextInput v-model="form.den_propano" :error="form.errors.den_propano" class="pb-4 pr-6" label="Propano - Densidad" />

          <TextInput v-model="form.iso_butano" :error="form.errors.iso_butano" class="pb-4 pr-6" label="Iso-Butano - PM" />
          <TextInput v-model="form.pe_iso_butano" :error="form.errors.pe_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % Peso" />
          <TextInput v-model="form.mo_iso_butano" :error="form.errors.mo_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % MOL" />
          <TextInput v-model="form.den_iso_butano" :error="form.errors.den_iso_butano" class="pb-4 pr-6" label="Iso-Butano - Densidad" />

          <TextInput v-model="form.n_butano" :error="form.errors.n_butano" class="pb-4 pr-6" label="n-Butano - PM" />
          <TextInput v-model="form.pe_n_butano" :error="form.errors.pe_n_butano" class="pb-4 pr-6" label="n-Butano - % Peso" />
          <TextInput v-model="form.mo_n_butano" :error="form.errors.mo_n_butano" class="pb-4 pr-6" label="n-Butano - % MOL" />
          <TextInput v-model="form.den_n_butano" :error="form.errors.den_n_butano" class="pb-4 pr-6" label="n-Butano - Densidad" />

          <TextInput v-model="form.iso_pentano" :error="form.errors.iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - PM" />
          <TextInput v-model="form.pe_iso_pentano" :error="form.errors.pe_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % Peso" />
          <TextInput v-model="form.mo_iso_pentano" :error="form.errors.mo_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % MOL" />
          <TextInput v-model="form.den_iso_pentano" :error="form.errors.den_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - Densidad" />

          <TextInput v-model="form.n_pentano" :error="form.errors.n_pentano" class="pb-4 pr-6" label="n-Pentano - PM" />
          <TextInput v-model="form.pe_n_pentano" :error="form.errors.pe_n_pentano" class="pb-4 pr-6" label="n-Pentano - % Peso" />
          <TextInput v-model="form.mo_n_pentano" :error="form.errors.mo_n_pentano" class="pb-4 pr-6" label="n-Pentano - % MOL" />
          <TextInput v-model="form.den_n_pentano" :error="form.errors.den_n_pentano" class="pb-4 pr-6" label="n-Pentano - Densidad" />

          <TextInput v-model="form.n_exano" :error="form.errors.n_exano" class="pb-4 pr-6" label="**n-Exano - PM" />
          <TextInput v-model="form.pe_n_exano" :error="form.errors.pe_n_exano" class="pb-4 pr-6" label="**n-Exano - % Peso" />
          <TextInput v-model="form.mo_n_exano" :error="form.errors.mo_n_exano" class="pb-4 pr-6" label="**n-Exano - % MOL" />
          <TextInput v-model="form.den_n_exano" :error="form.errors.den_n_exano" class="pb-4 pr-6" label="**n-Exano - Densidad" />

          <TextInput v-model="form.fecha_recep" :error="form.errors.fecha_recep" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de recepción" />
          <TextInput v-model="form.fecha_analisis" :error="form.errors.fecha_analisis" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de análisis" />
          <TextInput v-model="form.fecha_muestreo" :error="form.errors.fecha_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de muestreo" />
          <TextInput v-model="form.no_determinacion" :error="form.errors.no_determinacion" class="pb-4 pr-6 w-full lg:w-1/2" label="Número de determinación" />

          <TextInput v-model="form.equipo_utilizado" :error="form.errors.equipo_utilizado" class="pb-4 pr-6 w-full lg:w-1/2" label="Equipo utilizado" />
          <TextInput v-model="form.met_laboratorio" :error="form.errors.met_laboratorio" class="pb-4 pr-6 w-full lg:w-1/2" label="Método de laboratorio" />

          <TextareaInput v-model="form.observaciones" :error="form.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <div class="flex flex-shrink-0 flex-wrap items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="form.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModal">Cancelar</button>
        </div>
      </form>
      <!-- Modal footer -->
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

    <Modal :show="showChart" style="max-width: 1000px" @close="closeModalChart">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Gráfica de Líneas - % MOL</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalChart">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6">
          <Line id="my-chart-id" :options="chartOptions" :data="chartDataFormatted" />
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModalChart">Cerrar</button>
        </div>
      </div>
    </Modal>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap text-sm">
        <thead class="bg-white border-b-2">
          <tr>
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
          </tr>
        </thead>
        <tbody>
          <tr class="bg-gray-50">
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">{{ componentePozo.pozo.nombre_pozo }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="3">
              <span class="block bg-gray-400 text-white font-normal">Datos de análisis en laboratorio</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
              <span class="block bg-gray-400 text-white font-normal">Nombre del grupo</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Fecha de recepción</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Fecha de análisis</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Número de determinación</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
              <span class="block">{{ componentePozo.nombre_componente }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.fecha_recep }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.fecha_analisis }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.no_determinacion }}</span>
            </td>
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Equipo utilizado</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
              <span class="block bg-yellow-400 text-white">Método de laboratorio</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Fecha de muestreo</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.fecha_muestreo }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.equipo_utilizado }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
              <span class="block">
                {{ truncateMessageMetLab }}
                <a v-if="componentePozo.met_laboratorio?.length > 40" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalMessageMet">Ver Más...</a>
              </span>
            </td>
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white">Observaciones</span>
            </td>
            <td class="p-1 text-left leading-6 border-solid border border-gray-200" colspan="2">
              <span class="block">{{ truncateMessageObs }} <a v-if="componentePozo.observaciones?.length > 38" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalObs">Ver Más...</a></span>
            </td>
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">Información</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">PM</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">% Peso</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">% MOL</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">Densidad</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Dióxido de carbono</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.dioxido_carbono }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_dioxido_carbono }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Ácido sulfhídrico</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.acido_sulfidrico }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_acido_sulfidrico }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Nitrógeno</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.nitrogeno }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_nitrogeno }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Metano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.metano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_metano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Etano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.etano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_etano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Propano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.propano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_propano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Iso-Butano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.iso_butano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_iso_butano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">n-Butano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.n_butano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_n_butano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">Iso-Pentano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.iso_pentano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_iso_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">n-Petano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.n_pentano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_n_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">**n-Exano</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.n_exano }}</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">43</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">54</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block">{{ componentePozo.den_n_exano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-gray-400 text-white font-normal">Total</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white font-bold">540.86</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white font-bold">100.00</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white font-bold">100</span>
            </td>
            <td class="p-1 text-center leading-6 border-solid border border-gray-200">
              <span class="block bg-yellow-400 text-white font-bold">0</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            <td class="border text-left p-1" colspan="5">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                <li class="flex items-center justify-between py-3 pl-3 pr-4">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL ({{ quimicosData.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:underline" @click.prevent="openModalChart">Visualizar</a>
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
