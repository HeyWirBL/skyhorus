<script setup>
import { computed, inject, nextTick, ref, watch } from 'vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import { Line } from 'vue-chartjs'
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend } from 'chart.js'
import chartjsPluginDatalabels from 'chartjs-plugin-datalabels'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, chartjsPluginDatalabels)

const props = defineProps({
  can: Object,
  filters: Object,
  componentePozos: Object,
  pozos: Array,
})

const swal = inject('$swal')

const editComPozo = ref(false)
const importComPozo = ref(false)

const selected = ref([])
const selectAllComPozos = ref(false)
const editInputRef = ref(null)
const fileInput = ref(null)
const fileUpload = ref([])
const selectedCompozo = ref({})

const showMessageMetLab = ref(false)
const showMessageObs = ref(false)
const showChart = ref(false)

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

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

const componentePozoForm = useForm({})

const importComPozoForm = useForm({
  file: [],
  pozoId: '',
  fechaRecep: '',
  fechaAnalisis: '',
  fechaMuest: '',
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

const componentes = computed(() => props.componentePozos)

const selectedComponentePozo = computed(() => {
  return componentes.value.data.find((componentePozo) => componentePozo.id === selectedCompozo.value)
})

const isTrashed = computed(() => usePage().url.includes('trashed=only'))

/**
 * Converts a file size in bytes to the nearest unit of measurement (B, kB, MB, GB, etc.).
 * @param {number} size - The file size in bytes.
 * @param {number} [precision=2] - The number of decimal places to include in the output. Default is 2.
 * @returns {string} The formatted file size with the appropriate unit of measurement.
 */
const filesize = (size, precision = 2) => {
  const units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB']
  if (size < 0) {
    return 'Tipo de archivo inválido'
  } else if (size === 0) {
    return `0 ${units[0]}`
  } else if (size < 1) {
    return `${(size * 1024).toFixed(precision)} ${units[1]}`
  } else if (size >= Math.pow(1024, units.length - 1)) {
    return `${(size / Math.pow(1024, units.length - 1)).toFixed(precision)} ${units[units.length - 1]}`
  } else {
    const i = Math.floor(Math.log(size) / Math.log(1024))
    const val = (size / Math.pow(1024, i)).toFixed(precision)
    return `${val} ${units[i]}`
  }
}

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
          label: 'Total Mo',
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
  toggleAll(props.componentePozos.data, selected, selectAllComPozos)
}

const changeToggleAllComPozos = () => {
  changeToggleAll(props.componentePozos.data, selected, selectAllComPozos)
}

const reset = () => {
  form.value = mapValues(form.value, () => null)
}

const onFileChange = (e) => {
  fileUpload.value = e.target.files
}

const browseFiles = () => {
  fileInput.value.click()
}

const removeFile = () => {
  fileUpload.value = []
  fileInput.value = null
}

const truncateMessageMetLab = (messageMetLab, maxLength = 40) => {
  const metLab = messageMetLab ?? ''
  return metLab.length > maxLength ? metLab.substring(0, maxLength) : metLab || 'No tiene especificado un método de laboratorio.'
}

const truncateMessageObs = (messageObs, maxLength = 40) => {
  const observaciones = messageObs ?? ''
  return observaciones.length > maxLength ? observaciones.substring(0, maxLength) : observaciones || 'Aún no hay observaciones.'
}

const openModalImportForm = () => {
  importComPozo.value = true
}

const openModalEditForm = (componentePozo) => {
  // Set form field values
  editComPozoForm.id = componentePozo.id
  editComPozoForm.dioxido_carbono = componentePozo.dioxido_carbono
  editComPozoForm.pe_dioxido_carbono = componentePozo.pe_dioxido_carbono
  editComPozoForm.mo_dioxido_carbono = componentePozo.mo_dioxido_carbono
  editComPozoForm.den_dioxido_carbono = componentePozo.den_dioxido_carbono
  editComPozoForm.acido_sulfidrico = componentePozo.acido_sulfidrico
  editComPozoForm.pe_acido_sulfidrico = componentePozo.pe_acido_sulfidrico
  editComPozoForm.mo_acido_sulfidrico = componentePozo.mo_acido_sulfidrico
  editComPozoForm.den_acido_sulfidrico = componentePozo.den_acido_sulfidrico
  editComPozoForm.nitrogeno = componentePozo.nitrogeno
  editComPozoForm.pe_nitrogeno = componentePozo.pe_nitrogeno
  editComPozoForm.mo_nitrogeno = componentePozo.mo_nitrogeno
  editComPozoForm.den_nitrogeno = componentePozo.den_nitrogeno
  editComPozoForm.metano = componentePozo.metano
  editComPozoForm.pe_metano = componentePozo.pe_metano
  editComPozoForm.mo_metano = componentePozo.mo_metano
  editComPozoForm.den_metano = componentePozo.den_metano
  editComPozoForm.etano = componentePozo.etano
  editComPozoForm.pe_etano = componentePozo.pe_etano
  editComPozoForm.mo_etano = componentePozo.mo_etano
  editComPozoForm.den_etano = componentePozo.den_etano
  editComPozoForm.propano = componentePozo.propano
  editComPozoForm.pe_propano = componentePozo.pe_propano
  editComPozoForm.mo_propano = componentePozo.mo_propano
  editComPozoForm.den_propano = componentePozo.den_propano
  editComPozoForm.iso_butano = componentePozo.iso_butano
  editComPozoForm.pe_iso_butano = componentePozo.pe_iso_butano
  editComPozoForm.mo_iso_butano = componentePozo.mo_iso_butano
  editComPozoForm.den_iso_butano = componentePozo.den_iso_butano
  editComPozoForm.n_butano = componentePozo.n_butano
  editComPozoForm.pe_n_butano = componentePozo.pe_n_butano
  editComPozoForm.mo_n_butano = componentePozo.mo_n_butano
  editComPozoForm.den_n_butano = componentePozo.den_n_butano
  editComPozoForm.iso_pentano = componentePozo.iso_pentano
  editComPozoForm.pe_iso_pentano = componentePozo.pe_iso_pentano
  editComPozoForm.mo_iso_pentano = componentePozo.mo_iso_pentano
  editComPozoForm.den_iso_pentano = componentePozo.den_iso_pentano
  editComPozoForm.n_pentano = componentePozo.n_pentano
  editComPozoForm.pe_n_pentano = componentePozo.pe_n_pentano
  editComPozoForm.mo_n_pentano = componentePozo.mo_n_pentano
  editComPozoForm.den_n_pentano = componentePozo.den_n_pentano
  editComPozoForm.n_exano = componentePozo.n_exano
  editComPozoForm.pe_n_exano = componentePozo.pe_n_exano
  editComPozoForm.mo_n_exano = componentePozo.mo_n_exano
  editComPozoForm.den_n_exano = componentePozo.den_n_exano
  editComPozoForm.pozo_id = componentePozo.pozo_id
  editComPozoForm.fecha_recep = componentePozo.fecha_recep
  editComPozoForm.fecha_analisis = componentePozo.fecha_analisis
  editComPozoForm.no_determinacion = componentePozo.no_determinacion
  editComPozoForm.equipo_utilizado = componentePozo.equipo_utilizado
  editComPozoForm.met_laboratorio = componentePozo.met_laboratorio
  editComPozoForm.observaciones = componentePozo.observaciones
  editComPozoForm.nombre_componente = componentePozo.nombre_componente
  editComPozoForm.fecha_muestreo = componentePozo.fecha_muestreo

  editComPozo.value = true
  nextTick(() => editInputRef.value.focus())
}

const openModalMessageMetLab = (id) => {
  selectedCompozo.value = componentes.value.data.find((componentePozo) => componentePozo.id === id)
  showMessageMetLab.value = true
}

const openModalMessageObs = (id) => {
  selectedCompozo.value = componentes.value.data.find((componentePozo) => componentePozo.id === id)
  showMessageObs.value = true
}

const openModalChart = (id) => {
  selectedCompozo.value = id
  showChart.value = true
}

const closeModalEditComPozoForm = () => {
  editComPozo.value = false
  editComPozoForm.reset()
}

const closeModalImportForm = () => {
  importComPozo.value = false
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

const importExcel = () => {
  for (let i = 0; i < fileUpload.value.length; i++) {
    importComPozoForm.file[i] = fileUpload.value[i]
  }

  importComPozoForm.post('/componente-pozos/import', {
    preserveScroll: true,
    onSuccess: () => (importComPozo.value = false),
    onFinish: () => {
      if (importComPozoForm.hasErrors) {
        console.error('No se pudo importar los componentes.')
      }
    },
  })
}

const update = () => {
  editComPozoForm.post(`/componente-pozos/${editComPozoForm.id}`, {
    preserveScroll: true,
    onSuccess: () => (editComPozo.value = false),
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
      title: '¿Estás seguro de querer eliminar estos componentes de este pozo?',
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
      title: '¿Estás seguro de querer eliminar estos componentes de pozos?',
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

const restoreSelectedItems = () => {
  if (selected.value.length === 1) {
    swal({
      title: '¿Estás seguro de querer restablecer los componentes de este pozo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        componentePozoForm.put(`/componente-pozos/${selected.value}/restore`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllComPozos.value = false),
        })
      }
    })
  } else {
    swal({
      title: '¿Estás seguro de querer restablecer los componentes de estos pozos?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#CEA915',
      cancelButtonColor: '#BDBDBD',
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        componentePozoForm.put(`/componente-pozos?ids=${selected.value.join(',')}`, {
          onSuccess: () => (selected.value = []),
          onFinish: () => (selectAllComPozos.value = false),
        })
      }
    })
  }
}

watch(
  () => form.value,
  debounce(function () {
    router.get('/componente-pozos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)
</script>

<template>
  <div>
    <Head title="Componentes de Pozos" />
    <h1 class="mb-8 text-3xl font-bold">Componentes de Pozos</h1>
    <div class="flex items-center mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
    </div>
    <div class="flex items-center mb-6">
      <button v-if="can.createComponentePozo" class="btn-yellow mr-2" type="button" @click="openModalImportForm">
        <span>Importar</span>
        <span class="hidden md:inline">&nbsp;Excel</span>
      </button>
      <button v-if="componentePozos.data.length !== 0 && can.deleteComponentePozo && !isTrashed" class="btn-secondary" type="button" :disabled="!selectAllComPozos && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
      <button v-if="componentePozos.data.length !== 0 && can.restoreComponentePozo && isTrashed" class="btn-secondary" type="button" :disabled="!selectAllComPozos && !selected.length" @click="restoreSelectedItems">
        <span>Restablecer</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <!-- Import Com Pozo Form Modal -->
    <Modal :show="importComPozo">
      <!-- Modal content -->
      <div class="relative">
        <!-- Modal header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Importar Componentes de Pozo</h2>
          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalImportForm">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
      </div>

      <!-- Modal body -->
      <form @submit.prevent="importExcel">
        <div class="relative flex flex-wrap p-4">
          <div class="mb-4">
            <p class="text-sm text-gray-900">
              <span class="font-semibold mr-1">Sugerencia:</span>
              <span class="mr-2">Descargue el formato para importar componentes.</span>
              <a class="text-yellow-400 hover:underline" href="/formato">Descargar archivo</a>
            </p>
          </div>
          <div class="pb-4 w-full">
            <label class="form-label">Elige un archivo:</label>
            <div class="form-input p-0">
              <input ref="fileInput" type="file" accept=".xlsx,.xls,.csv" class="hidden" @change="onFileChange" />
              <div v-if="!fileUpload.length" class="p-2">
                <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded" @click="browseFiles">Elegir...</button>
              </div>
              <div v-else class="flex items-center justify-between p-2">
                <div class="flex-1 pr-1">
                  {{ fileUpload[0].name }} <span class="text-gray-500 text-xs">({{ filesize(fileUpload[0].size) }})</span>
                </div>
                <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded" @click.prevent="removeFile">Remover</button>
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-shrink-0 flex-wrap items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <LoadingButton :loading="importComPozoForm.processing" class="btn-yellow mr-2" type="submit">Importar</LoadingButton>
          <button class="btn-secondary" @click="closeModalImportForm">Cancelar</button>
        </div>
      </form>
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
          <SelectInput v-model="editComPozoForm.pozo_id" :error="editComPozoForm.errors.pozo_id" class="pb-8 pr-6 w-full lg:w-1/2" label="Pozo/Instalación">
            <option :value="null" />
            <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
          </SelectInput>
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
          <template v-for="(componentePozo, rowIndex) in componentePozos.data" :key="componentePozo.id">
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
              <td class="p-4 whitespace-nowrap border-solid border border-gray-200">
                <span v-if="can.editComponentePozo" class="inline-block whitespace-nowrap" title="Editar componentes de pozo">
                  <button class="flex items-center" tabindex="-1" type="button" @click="openModalEditForm(componentePozo)">
                    <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                  </button>
                </span>
              </td>
              <td class="w-4 p-4 border-solid border border-gray-200">
                <div v-if="can.deleteComponentePozo" class="flex items-center">
                  <input :id="`checkbox-compozo-${componentePozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="componentePozo.id" @change="changeToggleAllComPozos" />
                  <label :for="`checkbox-compozo-${componentePozo.id}`" class="sr-only">checkbox</label>
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
                <span class="block">{{ componentePozo.nombre_componente }}</span>
              </td>
            </tr>
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
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
            <tr class="bg-gray-50">
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.equipo_utilizado }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200" colspan="2">
                <span class="block">
                  {{ truncateMessageMetLab(componentePozo.met_laboratorio) }}
                  <a v-show="componentePozo.met_laboratorio?.length > 40" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalMessageMetLab(componentePozo.id)">Ver Más...</a>
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
                <span class="block">{{ truncateMessageObs(componentePozo.observaciones) }} <a v-show="componentePozo.observaciones?.length > 40" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalMessageObs(componentePozo.id)">Ver Más...</a></span>
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
                <span class="block">{{ componentePozo.dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_dioxido_carbono }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_dioxido_carbono }}</span>
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
                <span class="block">{{ componentePozo.acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_acido_sulfidrico }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_acido_sulfidrico }}</span>
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
                <span class="block">{{ componentePozo.nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_nitrogeno }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_nitrogeno }}</span>
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
                <span class="block">{{ componentePozo.metano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_metano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_metano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_metano }}</span>
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
                <span class="block">{{ componentePozo.etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_etano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_etano }}</span>
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
                <span class="block">{{ componentePozo.propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_propano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_propano }}</span>
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
                <span class="block">{{ componentePozo.iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_iso_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_iso_butano }}</span>
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
                <span class="block">{{ componentePozo.n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_n_butano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_n_butano }}</span>
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
                <span class="block">{{ componentePozo.iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_iso_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_iso_pentano }}</span>
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
                <span class="block">{{ componentePozo.n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_n_pentano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_n_pentano }}</span>
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
                <span class="block">{{ componentePozo.n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.pe_n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.mo_n_exano }}</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">{{ componentePozo.den_n_exano }}</span>
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
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="p-1 whitespace-nowrap border-solid border border-gray-200" />
              <td class="border text-left p-1" colspan="5">
                <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                  <li class="flex items-center justify-between py-3 pl-3 pr-4">
                    <div class="flex w-0 flex-1 items-center">
                      <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                      <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL ({{ componentePozo.quimicosData.length }})</span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                      <a href="#" class="font-medium text-yellow-600 hover:underline" @click.prevent="openModalChart(componentePozo.id)">Visualizar</a>
                    </div>
                  </li>
                </ul>
              </td>
            </tr>
          </template>
          <template v-if="componentePozos.data.length === 0">
            <tr class="bg-white">
              <td class="px-6 py-4 text-base text-left leading-6 border border-gray-200" colspan="5">No se encontraron componentes de pozos {{ form.trashed === 'only' ? 'eliminados' : 'registrados' }}.</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="componentePozos.links" :total="componentePozos.total" />
  </div>
</template>
