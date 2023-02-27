<template>
  <div>
    <TrashedMessage v-if="pozo.deleted_at && pozo.can.restorePozo" class="mb-6" @restore="restore">Este pozo ha sido eliminado.</TrashedMessage>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-white border-b">
          <tr>
            <th scope="col" class="border px-6 py-4" colspan="2">
              <button v-if="!pozo.deleted_at" class="text-red-600 font-medium hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Pozo</button>
            </th>
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" colspan="2" />
          </tr>
        </thead>
        <tbody>
          <tr class="bg-gray-50">
            <td class="border px-6 py-4" colspan="2" />
            <td class="border text-center px-6 py-4" colspan="4">
              <span class="block bg-gray-400 text-white font-bold leading-6">Datos del muestreo</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white leading-6">Identificador</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white leading-6">{{ pozo.id }}</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white leading-6">Pozo/Instalación</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white leading-6">Punto de muestreo</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 text-white leading-6">Observaciones</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-left border px-6 py-4" colspan="2" rowspan="5">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400 w-[20.063rem]">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="pencil" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Análisis ({{ pozo.docPozos.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#docpozos" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab = 'docpozos'">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Componentes ({{ pozo.componentePozos.data.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#componentepozos" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab = 'componentepozos'">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía de Gas ({{ pozo.cromatografiaGases.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#cromatografiagas" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab = 'cromatografiagas'">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate"> Cromatografía Líquida ({{ pozo.cromatografiaLiquidas.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#cromatografialiquida" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab = 'cromatografialiquida'">Mostrar</a>
                  </div>
                </li>
              </ul>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ pozo.nombre_pozo }}</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ pozo.punto_muestreo }}</span>
            </td>
            <td class="text-left border px-6 py-4" colspan="2">
              <span class="block">{{ pozo.observaciones === '' || pozo.observaciones === null ? 'Aún no hay observaciones.' : pozo.observaciones }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Fecha del muestreo</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Presión</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ pozo.fecha_hora }}</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ pozo.presion_kgcm2 }} KM/CM2 {{ pozo.presion_psi }} PSIA</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Temperatura</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Volúmen</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Identificador</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ pozo.temp_C }} °C {{ pozo.temp_F }} °F</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ pozo.volumen_cm3 }} CM3 {{ pozo.volumen_lts }} LTS</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ pozo.identificador === '' || pozo.identificador === null ? 'Sin identificador.' : pozo.identificador }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Document Analysis -->
    <div v-show="activeTab === 'docpozos'" id="docpozos" class="mt-12">
      <h2 class="mb-4 text-2xl font-bold">Documentos de Pozo</h2>
      <div class="flex items-center">
        <a class="btn-yellow mr-2" href="#">
          <span>Subir</span>
          <span class="hidden md:inline">&nbsp;Documentos</span>
        </a>
      </div>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <!-- DocPozoTable Component -->
        <DocPozoTable :pozo="pozo" />
      </div>
    </div>

    <!-- Well Components -->
    <div v-show="activeTab === 'componentepozos'" id="componentepozos" class="mt-12">
      <h2 class="mb-4 text-2xl font-bold">Componentes de Pozo</h2>
      <div class="flex items-center">
        <a class="btn-yellow mr-2" href="#">
          <span>Importar</span>
          <span class="hidden md:inline">&nbsp;Excel</span>
        </a>
      </div>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <!-- ComPozoTable Component -->
        <ComPozoTable :pozo="pozo" />
      </div>
    </div>

    <!-- Well Documents -->
    <div v-show="activeTab === 'cromatografiagas'" id="cromatografiagas" class="mt-12">
      <h2 class="mb-4 text-2xl font-bold">Cromatografía de Gas</h2>
      <div class="flex items-center">
        <a class="btn-yellow mr-2" href="#">
          <span>Subir</span>
          <span class="hidden md:inline">&nbsp;Documentos</span>
        </a>
      </div>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <!-- CromGasTable Component -->
        <CromGasTable :pozo="pozo" :selected="selected" @update:selected="updateSelectedCromGas" @toggle="toggleAll" @change="changeToggleAllCromGas" />
      </div>
    </div>

    <!-- Well Documents -->
    <div v-show="activeTab === 'cromatografialiquida'" id="cromatografialiquida" class="mt-12">
      <h2 class="mb-4 text-2xl font-bold">Cromatografía Líquida</h2>
      <div class="flex items-center">
        <a class="btn-yellow mr-2" href="#">
          <span>Subir</span>
          <span class="hidden md:inline">&nbsp;Documentos</span>
        </a>
      </div>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <!-- CromLiquidaTable Component -->
        <CromLiquidaTable :pozo="pozo" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, inject, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
/* Tables */
import DocPozoTable from './Tables/DocPozoTable.vue'
import ComPozoTable from './Tables/ComPozoTable.vue'
import CromGasTable from './Tables/CromGasTable.vue'
import CromLiquidaTable from './Tables/CromLiquidaTable.vue'

const props = defineProps({
  pozo: Object,
})

const swal = inject('$swal')

// State of tab navigation
const activeTab = ref('')
// Checkbox for separate state
const selected = ref([])
const selectAllDocPozos = ref(false)
const selectAllComPozos = ref(false)
const selectAllCromGases = ref(false)
const selectAllCromLiquidas = ref(false)

const cromatografiaGases = computed(() => props.pozo.cromatografiaGases)

// Pozo Form
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

const destroy = () => {
  swal({
    title: '¿Estás seguro de querer eliminar este pozo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      pozoForm.delete(`/pozos/${props.pozo.id}`)
    }
  })
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer este pozo?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Confirmar',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      pozoForm.put(`/pozos/${props.pozo.id}/restore`)
    }
  })
}

const updateSelectedCromGas = (value) => {
  selected.value = value
}

// Select All Methods
const toggleAllDocPozos = () => {
  toggleAll(docPozos.value, selected, selectAllDocPozos)
}

const toggleAllComPozos = () => {
  toggleAll(componentePozos.value.data, selected, selectAllComPozos)
}

const toggleAllCromGas = () => {
  toggleAll(cromatografiaGases.value, selected, selectAllCromGases)
}

const toggleAllCromLiquidas = () => {
  toggleAll(cromatografiaLiquidas.value, selected, selectAllCromLiquidas)
}

// Change State Methods
const changeToggleAllDocPozos = () => {
  changeToggleAll(docPozos.value, selected, selectAllDocPozos)
}

const changeToggleAllComPozos = () => {
  changeToggleAll(componentePozos.value.data, selected, selectAllComPozos)
}

const changeToggleAllCromGas = () => {
  changeToggleAll(cromatografiaGases.value, selected, selectAllCromGases)
}

const changeToggleAllCromLiquidas = () => {
  changeToggleAll(cromatografiaLiquidas.value, selected, selectAllCromLiquidas)
}
</script>
