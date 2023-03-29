<script setup>
import { computed, inject, nextTick, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'
import chartjsPluginDatalabels from 'chartjs-plugin-datalabels'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, chartjsPluginDatalabels)

const props = defineProps({
  can: Object,
  pozo: Object,
})

const swal = inject('$swal')

const createNewComPozo = ref(false)
const editComPozo = ref(false)

const selected = ref([])
const selectAllComPozos = ref(false)
const selectedCompozo = ref({})
const createInputRef = ref(null)
const editInputRef = ref(null)

const showMessageMetLab = ref(false)
const showMessageObs = ref(false)
const showChart = ref(false)

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
        text: 'Total % Mol',
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

const componentePozoForm = useForm({})

const createComPozoForm = useForm({
  dioxido_carbono: '',
  pe_dioxido_carbono: '',
  mo_dioxido_carbono: '',
  den_dioxido_carbono: '',
  acido_sulfidrico: '',
  pe_acido_sulfidrico: '',
  mo_acido_sulfidrico: '',
  den_acido_sulfidrico: '',
  nitrogeno: '',
  pe_nitrogeno: '',
  mo_nitrogeno: '',
  den_nitrogeno: '',
  metano: '',
  pe_metano: '',
  mo_metano: '',
  den_metano: '',
  etano: '',
  pe_etano: '',
  mo_etano: '',
  den_etano: '',
  propano: '',
  pe_propano: '',
  mo_propano: '',
  den_propano: '',
  iso_butano: '',
  pe_iso_butano: '',
  mo_iso_butano: '',
  den_iso_butano: '',
  n_butano: '',
  pe_n_butano: '',
  mo_n_butano: '',
  den_n_butano: '',
  iso_pentano: '',
  pe_iso_pentano: '',
  mo_iso_pentano: '',
  den_iso_pentano: '',
  n_pentano: '',
  pe_n_pentano: '',
  mo_n_pentano: '',
  den_n_pentano: '',
  n_exano: '',
  pe_n_exano: '',
  mo_n_exano: '',
  den_n_exano: '',
  pozo_id: props.pozo.id,
  fecha_recep: '',
  fecha_analisis: '',
  no_determinacion: '',
  equipo_utilizado: '',
  met_laboratorio: '',
  observaciones: null,
  nombre_componente: '',
  fecha_muestreo: null,
})

const editComPozoForm = useForm({
  _method: 'put',
  id: '',
  dioxido_carbono: '',
  pe_dioxido_carbono: '',
  mo_dioxido_carbono: '',
  den_dioxido_carbono: '',
  acido_sulfidrico: '',
  pe_acido_sulfidrico: '',
  mo_acido_sulfidrico: '',
  den_acido_sulfidrico: '',
  nitrogeno: '',
  pe_nitrogeno: '',
  mo_nitrogeno: '',
  den_nitrogeno: '',
  metano: '',
  pe_metano: '',
  mo_metano: '',
  den_metano: '',
  etano: '',
  pe_etano: '',
  mo_etano: '',
  den_etano: '',
  propano: '',
  pe_propano: '',
  mo_propano: '',
  den_propano: '',
  iso_butano: '',
  pe_iso_butano: '',
  mo_iso_butano: '',
  den_iso_butano: '',
  n_butano: '',
  pe_n_butano: '',
  mo_n_butano: '',
  den_n_butano: '',
  iso_pentano: '',
  pe_iso_pentano: '',
  mo_iso_pentano: '',
  den_iso_pentano: '',
  n_pentano: '',
  pe_n_pentano: '',
  mo_n_pentano: '',
  den_n_pentano: '',
  n_exano: '',
  pe_n_exano: '',
  mo_n_exano: '',
  den_n_exano: '',
  pozo_id: '',
  fecha_recep: '',
  fecha_analisis: '',
  no_determinacion: '',
  equipo_utilizado: '',
  met_laboratorio: '',
  observaciones: '',
  nombre_componente: '',
  fecha_muestreo: '',
})

const componentePozos = computed(() => props.pozo.componentePozos)

const selectedComponentePozo = computed(() => {
  return componentePozos.value.data.find((componentePozo) => componentePozo.id === selectedCompozo.value)
})

/**
 * Computed Property: Formats the data received from the props in a format
 * that can be used by the chart. This includes mapping the `quimicosData`
 * array to generate the chart labels and data.
 */
const chartDataFormatted = computed(() => {
  if (selectedComponentePozo.value) {
    const quimicosData = selectedComponentePozo.value.quimicosData
    return {
      labels: quimicosData.map((q) => q.Quimico),
      datasets: [
        {
          label: 'Total % Mol',
          data: quimicosData.map((q) => q.Total_mo),
          backgroundColor: 'rgba(100, 181, 246, 1)',
          borderColor: 'rgba(105, 183, 246, 1)',
          borderWidth: 2,
          pointStyle: 'circle',
          pointRadius: 6,
          pointHoverRadius: 12,
        },
      ],
    }
  } else {
    return {}
  }
})

/**
 * Helper Function that that checks whether the `selectAllRef` flag is set
 * to false.
 *
 * @param {array} items an array of items.
 * @param {array} selectedItems an array of selected items.
 * @param {bool} selectAllRef boolean flag that represents whether all items are selected.
 */
const toggleAll = (items, selectedItems, selectAllRef) => {
  selectedItems.value = []
  if (!selectAllRef.value) {
    selectedItems.value = selectedItems.value.length === items.length ? [] : items.map((item) => item.id)
  }
}

/**
 * Helper Function that updates the state of the "select all" checkbox
 * when individual checkboxes are checked or unchecked.
 *
 * @param {array} items list of items that can be selected.
 * @param {array} selectedItems an array which contains the ids of the items that have been selected.
 * @param {bool} selectAllRef reference that represents the state of the "select all" checkbox.
 */
const changeToggleAll = (items, selectedItems, selectAllRef) => {
  if (items.length === selectedItems.value.length) {
    selectAllRef.value = true
  } else {
    selectAllRef.value = false
  }
}

const toggleAllComPozos = () => {
  toggleAll(componentePozos.value.data, selected, selectAllComPozos)
}

const changeToggleAllComPozos = () => {
  changeToggleAll(componentePozos.value.data, selected, selectAllComPozos)
}

const openModalCreateComPozoForm = () => {
  createNewComPozo.value = true

  nextTick(() => createInputRef.value.focus())
}

const openModalEditComPozoForm = (compozo) => {
  // Set form field values
  editComPozoForm.id = compozo.id
  editComPozoForm.dioxido_carbono = compozo.dioxido_carbono
  editComPozoForm.pe_dioxido_carbono = compozo.pe_dioxido_carbono
  editComPozoForm.mo_dioxido_carbono = compozo.mo_dioxido_carbono
  editComPozoForm.den_dioxido_carbono = compozo.den_dioxido_carbono
  editComPozoForm.acido_sulfidrico = compozo.acido_sulfidrico
  editComPozoForm.pe_acido_sulfidrico = compozo.pe_acido_sulfidrico
  editComPozoForm.mo_acido_sulfidrico = compozo.mo_acido_sulfidrico
  editComPozoForm.den_acido_sulfidrico = compozo.den_acido_sulfidrico
  editComPozoForm.nitrogeno = compozo.nitrogeno
  editComPozoForm.pe_nitrogeno = compozo.pe_nitrogeno
  editComPozoForm.mo_nitrogeno = compozo.mo_nitrogeno
  editComPozoForm.den_nitrogeno = compozo.den_nitrogeno
  editComPozoForm.metano = compozo.metano
  editComPozoForm.pe_metano = compozo.pe_metano
  editComPozoForm.mo_metano = compozo.mo_metano
  editComPozoForm.den_metano = compozo.den_metano
  editComPozoForm.etano = compozo.etano
  editComPozoForm.pe_etano = compozo.pe_etano
  editComPozoForm.mo_etano = compozo.mo_etano
  editComPozoForm.den_etano = compozo.den_etano
  editComPozoForm.propano = compozo.propano
  editComPozoForm.pe_propano = compozo.pe_propano
  editComPozoForm.mo_propano = compozo.mo_propano
  editComPozoForm.den_propano = compozo.den_propano
  editComPozoForm.iso_butano = compozo.iso_butano
  editComPozoForm.pe_iso_butano = compozo.pe_iso_butano
  editComPozoForm.mo_iso_butano = compozo.mo_iso_butano
  editComPozoForm.den_iso_butano = compozo.den_iso_butano
  editComPozoForm.n_butano = compozo.n_butano
  editComPozoForm.pe_n_butano = compozo.pe_n_butano
  editComPozoForm.mo_n_butano = compozo.mo_n_butano
  editComPozoForm.den_n_butano = compozo.den_n_butano
  editComPozoForm.iso_pentano = compozo.iso_pentano
  editComPozoForm.pe_iso_pentano = compozo.pe_iso_pentano
  editComPozoForm.mo_iso_pentano = compozo.mo_iso_pentano
  editComPozoForm.den_iso_pentano = compozo.den_iso_pentano
  editComPozoForm.n_pentano = compozo.n_pentano
  editComPozoForm.pe_n_pentano = compozo.pe_n_pentano
  editComPozoForm.mo_n_pentano = compozo.mo_n_pentano
  editComPozoForm.den_n_pentano = compozo.den_n_pentano
  editComPozoForm.n_exano = compozo.n_exano
  editComPozoForm.pe_n_exano = compozo.pe_n_exano
  editComPozoForm.mo_n_exano = compozo.mo_n_exano
  editComPozoForm.den_n_exano = compozo.den_n_exano
  editComPozoForm.pozo_id = props.pozo.id
  editComPozoForm.fecha_recep = compozo.fecha_recep
  editComPozoForm.fecha_analisis = compozo.fecha_analisis
  editComPozoForm.no_determinacion = compozo.no_determinacion
  editComPozoForm.equipo_utilizado = compozo.equipo_utilizado
  editComPozoForm.met_laboratorio = compozo.met_laboratorio
  editComPozoForm.observaciones = compozo.observaciones
  editComPozoForm.nombre_componente = compozo.nombre_componente
  editComPozoForm.fecha_muestreo = compozo.fecha_muestreo

  editComPozo.value = true
  nextTick(() => editInputRef.value.focus())
}

const openModalMessageMetLab = (id) => {
  selectedCompozo.value = componentePozos.value.data.find((compozo) => compozo.id === id)
  showMessageMetLab.value = true
}

const openModalMessageObs = (id) => {
  selectedCompozo.value = componentePozos.value.data.find((compozo) => compozo.id === id)
  showMessageObs.value = true
}

const openModalChart = (id) => {
  selectedCompozo.value = id
  showChart.value = true
}

const closeModalCreateComPozoForm = () => {
  createNewComPozo.value = false
}

const closeModalEditComPozoForm = () => {
  editComPozo.value = false
  editComPozoForm.reset()
}

const closeModalMessageMetLab = () => {
  showMessageMetLab.value = false
}

const closeModalMessageObs = () => {
  showMessageObs.value = false
}

const closeModalChart = () => {
  showChart.value = false
}

const store = () => {
  createComPozoForm.post('/componente-pozos', {
    preserveScroll: true,
    onSuccess: () => closeModalCreateComPozoForm(),
    onError: () => createInputRef.value.focus(),
  })
}

const update = () => {
  editComPozoForm.post(`/componente-pozos/${editComPozoForm.id}`, {
    preserveScroll: true,
    onSuccess: () => closeModalEditComPozoForm(),
    onError: () => editInputRef.value.focus(),
    onFinish: () => {
      if (!editComPozoForm.hasErrors) {
        editComPozoForm.reset()
      }
    },
  })
}

const removeSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer eliminar estos componentes de pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        if (result.isConfirmed) {
          componentePozoForm.delete(`/componente-pozos/${selected.value}`, {
            onSuccess: () => (selected.value = []),
            onFinish: () => (selectAllComPozos.value = false),
          })
        }
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer eliminar estos componentes de pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        componentePozoForm.delete(`/componente-pozos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllComPozos.value = false),
        })
      }
    })
  }
}
</script>

<template>
  <div>
    <h2 class="mb-6 text-2xl font-bold">Componentes de Pozo</h2>
    <div class="flex items-center mb-6">
      <button v-if="can.createComponentePozo" class="btn-yellow mr-2" type="button" @click="openModalCreateComPozoForm">
        <span>Añadir</span>
        <span class="hidden md:inline">&nbsp;Componentes</span>
      </button>
      <button v-if="componentePozos.data.length !== 0 && can.deleteComponentePozo" class="btn-secondary" type="button" :disabled="!selectAllComPozos && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Create Com Pozo Form Modal -->
    <Modal :show="createNewComPozo" style="max-width: 1015px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Crear Componentes de Pozo</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalCreateComPozoForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="store">
        <div class="relative flex flex-wrap p-4 overflow-y-auto" style="height: 513.6px">
          <TextInput ref="createInputRef" v-model="createComPozoForm.nombre_componente" :error="createComPozoForm.errors.nombre_componente" class="pb-4 pr-6 w-full lg:w-1/2" label="Nombre del grupo de componentes" />
          <div class="pb-4 pr-6 w-full lg:w-1/2">
            <label class="form-label" for="text-input-pozo">Pozo/Instalación:</label>
            <div class="pt-2">
              <span class="block">{{ pozo.nombre_pozo }}</span>
            </div>
            <input id="text-input-pozo" type="hidden" :value="pozo.id" />
          </div>
          <TextInput v-model="createComPozoForm.dioxido_carbono" :error="createComPozoForm.errors.dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - PM" />
          <TextInput v-model="createComPozoForm.pe_dioxido_carbono" :error="createComPozoForm.errors.pe_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % Peso" />
          <TextInput v-model="createComPozoForm.mo_dioxido_carbono" :error="createComPozoForm.errors.mo_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % MOL" />
          <TextInput v-model="createComPozoForm.den_dioxido_carbono" :error="createComPozoForm.errors.den_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - Densidad" />

          <TextInput v-model="createComPozoForm.acido_sulfidrico" :error="createComPozoForm.errors.acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - PM" />
          <TextInput v-model="createComPozoForm.pe_acido_sulfidrico" :error="createComPozoForm.errors.pe_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % Peso" />
          <TextInput v-model="createComPozoForm.mo_acido_sulfidrico" :error="createComPozoForm.errors.mo_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % MOL" />
          <TextInput v-model="createComPozoForm.den_acido_sulfidrico" :error="createComPozoForm.errors.den_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - Densidad" />

          <TextInput v-model="createComPozoForm.nitrogeno" :error="createComPozoForm.errors.nitrogeno" class="pb-4 pr-6" label="Nitrógeno - PM" />
          <TextInput v-model="createComPozoForm.pe_nitrogeno" :error="createComPozoForm.errors.pe_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % Peso" />
          <TextInput v-model="createComPozoForm.mo_nitrogeno" :error="createComPozoForm.errors.mo_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % MOL" />
          <TextInput v-model="createComPozoForm.den_nitrogeno" :error="createComPozoForm.errors.den_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - Densidad" />

          <TextInput v-model="createComPozoForm.metano" :error="createComPozoForm.errors.metano" class="pb-4 pr-6" label="Metano - PM" />
          <TextInput v-model="createComPozoForm.pe_metano" :error="createComPozoForm.errors.pe_metano" class="pb-4 pr-6" label="Metano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_metano" :error="createComPozoForm.errors.mo_metano" class="pb-4 pr-6" label="Metano - % MOL" />
          <TextInput v-model="createComPozoForm.den_metano" :error="createComPozoForm.errors.den_metano" class="pb-4 pr-6" label="Metano - Densidad" />

          <TextInput v-model="createComPozoForm.etano" :error="createComPozoForm.errors.etano" class="pb-4 pr-6" label="Etano - PM" />
          <TextInput v-model="createComPozoForm.pe_etano" :error="createComPozoForm.errors.pe_etano" class="pb-4 pr-6" label="Etano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_etano" :error="createComPozoForm.errors.mo_etano" class="pb-4 pr-6" label="Etano - % MOL" />
          <TextInput v-model="createComPozoForm.den_etano" :error="createComPozoForm.errors.den_etano" class="pb-4 pr-6" label="Etano - Densidad" />

          <TextInput v-model="createComPozoForm.propano" :error="createComPozoForm.errors.propano" class="pb-4 pr-6" label="Propano - PM" />
          <TextInput v-model="createComPozoForm.pe_propano" :error="createComPozoForm.errors.pe_propano" class="pb-4 pr-6" label="Propano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_propano" :error="createComPozoForm.errors.mo_propano" class="pb-4 pr-6" label="Propano - % MOL" />
          <TextInput v-model="createComPozoForm.den_propano" :error="createComPozoForm.errors.den_propano" class="pb-4 pr-6" label="Propano - Densidad" />

          <TextInput v-model="createComPozoForm.iso_butano" :error="createComPozoForm.errors.iso_butano" class="pb-4 pr-6" label="Iso-Butano - PM" />
          <TextInput v-model="createComPozoForm.pe_iso_butano" :error="createComPozoForm.errors.pe_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_iso_butano" :error="createComPozoForm.errors.mo_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % MOL" />
          <TextInput v-model="createComPozoForm.den_iso_butano" :error="createComPozoForm.errors.den_iso_butano" class="pb-4 pr-6" label="Iso-Butano - Densidad" />

          <TextInput v-model="createComPozoForm.n_butano" :error="createComPozoForm.errors.n_butano" class="pb-4 pr-6" label="n-Butano - PM" />
          <TextInput v-model="createComPozoForm.pe_n_butano" :error="createComPozoForm.errors.pe_n_butano" class="pb-4 pr-6" label="n-Butano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_n_butano" :error="createComPozoForm.errors.mo_n_butano" class="pb-4 pr-6" label="n-Butano - % MOL" />
          <TextInput v-model="createComPozoForm.den_n_butano" :error="createComPozoForm.errors.den_n_butano" class="pb-4 pr-6" label="n-Butano - Densidad" />

          <TextInput v-model="createComPozoForm.iso_pentano" :error="createComPozoForm.errors.iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - PM" />
          <TextInput v-model="createComPozoForm.pe_iso_pentano" :error="createComPozoForm.errors.pe_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_iso_pentano" :error="createComPozoForm.errors.mo_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % MOL" />
          <TextInput v-model="createComPozoForm.den_iso_pentano" :error="createComPozoForm.errors.den_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - Densidad" />

          <TextInput v-model="createComPozoForm.n_pentano" :error="createComPozoForm.errors.n_pentano" class="pb-4 pr-6" label="n-Pentano - PM" />
          <TextInput v-model="createComPozoForm.pe_n_pentano" :error="createComPozoForm.errors.pe_n_pentano" class="pb-4 pr-6" label="n-Pentano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_n_pentano" :error="createComPozoForm.errors.mo_n_pentano" class="pb-4 pr-6" label="n-Pentano - % MOL" />
          <TextInput v-model="createComPozoForm.den_n_pentano" :error="createComPozoForm.errors.den_n_pentano" class="pb-4 pr-6" label="n-Pentano - Densidad" />

          <TextInput v-model="createComPozoForm.n_exano" :error="createComPozoForm.errors.n_exano" class="pb-4 pr-6" label="**n-Exano - PM" />
          <TextInput v-model="createComPozoForm.pe_n_exano" :error="createComPozoForm.errors.pe_n_exano" class="pb-4 pr-6" label="**n-Exano - % Peso" />
          <TextInput v-model="createComPozoForm.mo_n_exano" :error="createComPozoForm.errors.mo_n_exano" class="pb-4 pr-6" label="**n-Exano - % MOL" />
          <TextInput v-model="createComPozoForm.den_n_exano" :error="createComPozoForm.errors.den_n_exano" class="pb-4 pr-6" label="**n-Exano - Densidad" />

          <TextInput v-model="createComPozoForm.fecha_recep" :error="createComPozoForm.errors.fecha_recep" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de recepción" />
          <TextInput v-model="createComPozoForm.fecha_analisis" :error="createComPozoForm.errors.fecha_analisis" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de análisis" />
          <TextInput v-model="createComPozoForm.fecha_muestreo" :error="createComPozoForm.errors.fecha_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de muestreo" />
          <TextInput v-model="createComPozoForm.no_determinacion" :error="createComPozoForm.errors.no_determinacion" class="pb-4 pr-6 w-full lg:w-1/2" label="Número de determinación" />

          <TextInput v-model="createComPozoForm.equipo_utilizado" :error="createComPozoForm.errors.equipo_utilizado" class="pb-4 pr-6 w-full lg:w-1/2" label="Equipo utilizado" />
          <TextInput v-model="createComPozoForm.met_laboratorio" :error="createComPozoForm.errors.met_laboratorio" class="pb-4 pr-6 w-full lg:w-1/2" label="Método de laboratorio" />

          <TextareaInput v-model="createComPozoForm.observaciones" :error="createComPozoForm.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <div class="flex flex-shrink-0 flex-wrap items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="createComPozoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalCreateComPozoForm">Cancelar</button>
        </div>
      </form>
      <!-- Modal footer -->
    </Modal>

    <!-- Edit Com Pozo Form Modal -->
    <Modal :show="editComPozo" style="max-width: 1015px">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Editar Componentes Pozo [{{ editComPozoForm.id }}]</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalEditComPozoForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>
      <!-- Modal body -->
      <form @submit.prevent="update">
        <div class="relative flex flex-wrap p-4 overflow-y-auto" style="height: 513.6px">
          <TextInput ref="editInputRef" v-model="editComPozoForm.nombre_componente" :error="editComPozoForm.errors.nombre_componente" class="pb-4 pr-6 w-full lg:w-1/2" label="Nombre del grupo de componentes" />
          <div class="pb-4 pr-6 w-full lg:w-1/2">
            <label class="form-label" for="text-input-pozo">Pozo/Instalación:</label>
            <div class="pt-2">
              <span class="block">{{ pozo.nombre_pozo }}</span>
            </div>
            <input id="text-input-pozo" type="hidden" :value="pozo.id" />
          </div>
          <TextInput v-model="editComPozoForm.dioxido_carbono" :error="editComPozoForm.errors.dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - PM" />
          <TextInput v-model="editComPozoForm.pe_dioxido_carbono" :error="editComPozoForm.errors.pe_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % Peso" />
          <TextInput v-model="editComPozoForm.mo_dioxido_carbono" :error="editComPozoForm.errors.mo_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - % MOL" />
          <TextInput v-model="editComPozoForm.den_dioxido_carbono" :error="editComPozoForm.errors.den_dioxido_carbono" class="pb-4 pr-6" label="Dióxido de carbono - Densidad" />

          <TextInput v-model="editComPozoForm.acido_sulfidrico" :error="editComPozoForm.errors.acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - PM" />
          <TextInput v-model="editComPozoForm.pe_acido_sulfidrico" :error="editComPozoForm.errors.pe_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % Peso" />
          <TextInput v-model="editComPozoForm.mo_acido_sulfidrico" :error="editComPozoForm.errors.mo_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - % MOL" />
          <TextInput v-model="editComPozoForm.den_acido_sulfidrico" :error="editComPozoForm.errors.den_acido_sulfidrico" class="pb-4 pr-6" label="Ácido sulfhídrico - Densidad" />

          <TextInput v-model="editComPozoForm.nitrogeno" :error="editComPozoForm.errors.nitrogeno" class="pb-4 pr-6" label="Nitrógeno - PM" />
          <TextInput v-model="editComPozoForm.pe_nitrogeno" :error="editComPozoForm.errors.pe_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % Peso" />
          <TextInput v-model="editComPozoForm.mo_nitrogeno" :error="editComPozoForm.errors.mo_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - % MOL" />
          <TextInput v-model="editComPozoForm.den_nitrogeno" :error="editComPozoForm.errors.den_nitrogeno" class="pb-4 pr-6" label="Nitrógeno - Densidad" />

          <TextInput v-model="editComPozoForm.metano" :error="editComPozoForm.errors.metano" class="pb-4 pr-6" label="Metano - PM" />
          <TextInput v-model="editComPozoForm.pe_metano" :error="editComPozoForm.errors.pe_metano" class="pb-4 pr-6" label="Metano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_metano" :error="editComPozoForm.errors.mo_metano" class="pb-4 pr-6" label="Metano - % MOL" />
          <TextInput v-model="editComPozoForm.den_metano" :error="editComPozoForm.errors.den_metano" class="pb-4 pr-6" label="Metano - Densidad" />

          <TextInput v-model="editComPozoForm.etano" :error="editComPozoForm.errors.etano" class="pb-4 pr-6" label="Etano - PM" />
          <TextInput v-model="editComPozoForm.pe_etano" :error="editComPozoForm.errors.pe_etano" class="pb-4 pr-6" label="Etano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_etano" :error="editComPozoForm.errors.mo_etano" class="pb-4 pr-6" label="Etano - % MOL" />
          <TextInput v-model="editComPozoForm.den_etano" :error="editComPozoForm.errors.den_etano" class="pb-4 pr-6" label="Etano - Densidad" />

          <TextInput v-model="editComPozoForm.propano" :error="editComPozoForm.errors.propano" class="pb-4 pr-6" label="Propano - PM" />
          <TextInput v-model="editComPozoForm.pe_propano" :error="editComPozoForm.errors.pe_propano" class="pb-4 pr-6" label="Propano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_propano" :error="editComPozoForm.errors.mo_propano" class="pb-4 pr-6" label="Propano - % MOL" />
          <TextInput v-model="editComPozoForm.den_propano" :error="editComPozoForm.errors.den_propano" class="pb-4 pr-6" label="Propano - Densidad" />

          <TextInput v-model="editComPozoForm.iso_butano" :error="editComPozoForm.errors.iso_butano" class="pb-4 pr-6" label="Iso-Butano - PM" />
          <TextInput v-model="editComPozoForm.pe_iso_butano" :error="editComPozoForm.errors.pe_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_iso_butano" :error="editComPozoForm.errors.mo_iso_butano" class="pb-4 pr-6" label="Iso-Butano - % MOL" />
          <TextInput v-model="editComPozoForm.den_iso_butano" :error="editComPozoForm.errors.den_iso_butano" class="pb-4 pr-6" label="Iso-Butano - Densidad" />

          <TextInput v-model="editComPozoForm.n_butano" :error="editComPozoForm.errors.n_butano" class="pb-4 pr-6" label="n-Butano - PM" />
          <TextInput v-model="editComPozoForm.pe_n_butano" :error="editComPozoForm.errors.pe_n_butano" class="pb-4 pr-6" label="n-Butano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_n_butano" :error="editComPozoForm.errors.mo_n_butano" class="pb-4 pr-6" label="n-Butano - % MOL" />
          <TextInput v-model="editComPozoForm.den_n_butano" :error="editComPozoForm.errors.den_n_butano" class="pb-4 pr-6" label="n-Butano - Densidad" />

          <TextInput v-model="editComPozoForm.iso_pentano" :error="editComPozoForm.errors.iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - PM" />
          <TextInput v-model="editComPozoForm.pe_iso_pentano" :error="editComPozoForm.errors.pe_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_iso_pentano" :error="editComPozoForm.errors.mo_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - % MOL" />
          <TextInput v-model="editComPozoForm.den_iso_pentano" :error="editComPozoForm.errors.den_iso_pentano" class="pb-4 pr-6" label="Iso-Pentano - Densidad" />

          <TextInput v-model="editComPozoForm.n_pentano" :error="editComPozoForm.errors.n_pentano" class="pb-4 pr-6" label="n-Pentano - PM" />
          <TextInput v-model="editComPozoForm.pe_n_pentano" :error="editComPozoForm.errors.pe_n_pentano" class="pb-4 pr-6" label="n-Pentano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_n_pentano" :error="editComPozoForm.errors.mo_n_pentano" class="pb-4 pr-6" label="n-Pentano - % MOL" />
          <TextInput v-model="editComPozoForm.den_n_pentano" :error="editComPozoForm.errors.den_n_pentano" class="pb-4 pr-6" label="n-Pentano - Densidad" />

          <TextInput v-model="editComPozoForm.n_exano" :error="editComPozoForm.errors.n_exano" class="pb-4 pr-6" label="**n-Exano - PM" />
          <TextInput v-model="editComPozoForm.pe_n_exano" :error="editComPozoForm.errors.pe_n_exano" class="pb-4 pr-6" label="**n-Exano - % Peso" />
          <TextInput v-model="editComPozoForm.mo_n_exano" :error="editComPozoForm.errors.mo_n_exano" class="pb-4 pr-6" label="**n-Exano - % MOL" />
          <TextInput v-model="editComPozoForm.den_n_exano" :error="editComPozoForm.errors.den_n_exano" class="pb-4 pr-6" label="**n-Exano - Densidad" />

          <TextInput v-model="editComPozoForm.fecha_recep" :error="editComPozoForm.errors.fecha_recep" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de recepción" />
          <TextInput v-model="editComPozoForm.fecha_analisis" :error="editComPozoForm.errors.fecha_analisis" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de análisis" />
          <TextInput v-model="editComPozoForm.fecha_muestreo" :error="editComPozoForm.errors.fecha_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" type="date" label="Fecha de muestreo" />
          <TextInput v-model="editComPozoForm.no_determinacion" :error="editComPozoForm.errors.no_determinacion" class="pb-4 pr-6 w-full lg:w-1/2" label="Número de determinación" />

          <TextInput v-model="editComPozoForm.equipo_utilizado" :error="editComPozoForm.errors.equipo_utilizado" class="pb-4 pr-6 w-full lg:w-1/2" label="Equipo utilizado" />
          <TextInput v-model="editComPozoForm.met_laboratorio" :error="editComPozoForm.errors.met_laboratorio" class="pb-4 pr-6 w-full lg:w-1/2" label="Método de laboratorio" />

          <TextareaInput v-model="editComPozoForm.observaciones" :error="editComPozoForm.errors.observaciones" class="pb-8 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
        </div>
        <div class="flex flex-shrink-0 flex-wrap items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="editComPozoForm.processing" class="btn-yellow mr-2" type="submit">Guardar</LoadingButton>
          <button class="btn-secondary" @click="closeModalEditComPozoForm">Cancelar</button>
        </div>
      </form>
      <!-- Modal footer -->
    </Modal>

    <Modal :show="showMessageMetLab" @close="closeModalMessageMetLab">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Método de laboratorio</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalMessageMetLab">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 150px">
          <p class="text-base text-gray-600">{{ selectedCompozo.met_laboratorio }}</p>
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModalMessageMetLab">Cerrar</button>
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
          <p class="text-base text-gray-600">{{ selectedCompozo.observaciones }}</p>
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
          <tr v-if="componentePozos.data.length !== 0">
            <th scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th scope="col" class="p-4 border-solid border border-gray-200">
              <div v-if="can.editComponentePozo" class="flex items-center">
                <input id="checkbox-all-compozos" v-model="selectAllComPozos" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllComPozos" />
                <label for="checkbox-all-compozos" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
            <th scope="col" class="p-1 border-solid border border-gray-200" />
          </tr>
        </thead>
        <tbody>
          <template v-for="(compozo, rowIndex) in componentePozos.data" :key="compozo.id">
            <tr v-if="rowIndex !== 0" class="bg-gray-200">
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
              <td class="px-6 py-3 whitespace-nowrap border-solid border border-gray-300" />
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-gray-400 text-white font-normal">{{ pozo.nombre_pozo }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="3">
                <span class="block bg-gray-400 text-white font-normal">Datos de análisis en laboratorio</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
                <span class="block bg-gray-400 text-white font-normal">Nombre del grupo</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-4 whitespace-nowrap border-solid border border-gray-200">
                <span v-if="can.editComponentePozo" class="inline-block whitespace-nowrap" title="Editar componentes de pozo">
                  <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditComPozoForm(compozo)">
                    <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                  </button>
                </span>
              </td>
              <td class="w-4 p-4 border-solid border border-gray-200">
                <div v-if="can.editComponentePozo" class="flex items-center">
                  <input :id="`checkbox-compozo-${compozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="compozo.id" @change="changeToggleAllComPozos" />
                  <label :for="`checkbox-compozo-${compozo.id}`" class="sr-only">checkbox</label>
                </div>
              </td>
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
                <span class="block">{{ compozo.nombre_componente }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.fecha_recep }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.fecha_analisis }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.no_determinacion }}</span>
              </td>
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
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
                <span class="block">{{ compozo.fecha_muestreo }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.equipo_utilizado }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
                <span class="block">
                  {{ compozo.met_laboratorio.substring(0, 40) }}
                  <a v-if="compozo.met_laboratorio.length > 40" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalMessageMetLab(compozo.id)">Ver Más...</a>
                </span>
              </td>
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-yellow-400 text-white">Observaciones</span>
              </td>
              <td class="p-1 text-left leading-6 border-solid border border-gray-200" colspan="2">
                <span class="block">{{ compozo.observaciones === '' || compozo.observaciones === null ? 'Aún no hay observaciones.' : compozo.observaciones?.substring(0, 38) }} <a v-if="compozo.observaciones?.length > 38" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalMessageObs(compozo.id)">Ver Más...</a></span>
              </td>
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
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
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Dióxido de carbono</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_dioxido_carbono }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Ácido sulfhídrico</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_acido_sulfidrico }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Nitrógeno</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_nitrogeno }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Metano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.metano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_metano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_metano }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Etano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_etano }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Propano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_propano }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Iso-Butano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_iso_butano }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">n-Butano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_n_butano }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">Iso-Pentano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_iso_pentano }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">n-Petano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_n_pentano }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">**n-Exano</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.pe_n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.mo_n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ compozo.den_n_exano }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-gray-400 text-white font-normal">Total</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-yellow-400 text-white font-bold">{{ compozo.totalData[0].Total_PM }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-yellow-400 text-white font-bold">{{ compozo.totalData[0].Total_Peso }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-yellow-400 text-white font-bold">{{ compozo.totalData[0].Total_MOL }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block bg-yellow-400 text-white font-bold">{{ compozo.totalData[0].Total_Densidad }}</span>
              </td>
            </tr>
            <tr class="bg-white">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="border text-left p-1" colspan="5">
                <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                  <li class="flex items-center justify-between py-3 pl-3 pr-4">
                    <div class="flex w-0 flex-1 items-center">
                      <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                      <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL ({{ compozo.quimicosData.length }})</span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                      <a href="#" class="font-medium text-yellow-600 hover:underline" @click.prevent="openModalChart(compozo.id)">Visualizar</a>
                    </div>
                  </li>
                </ul>
              </td>
            </tr>
          </template>
          <template v-if="componentePozos.data.length === 0">
            <tr class="bg-white">
              <td class="px-6 py-4 text-base text-left leading-6 border-solid border border-gray-200" colspan="5">No se encontraron componentes registrados en este pozo.</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="componentePozos.links" :total="componentePozos.total" />
  </div>
</template>
