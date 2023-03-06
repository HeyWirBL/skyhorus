<script setup>
import { computed, ref } from 'vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const emit = defineEmits(['active'])

const showFullMessage = ref(false)

// Computed collections
const docPozos = computed(() => props.pozo.docPozos.data)
const componentePozos = computed(() => props.pozo.componentePozos.data)
const cromatografiaGases = computed(() => props.pozo.cromatografiaGases.data)
const cromatografiaLiquidas = computed(() => props.pozo.cromatografiaLiquidas.data)

const activeTab = (tabName) => {
  emit('active', tabName)
}

const truncateMessage = computed(() => {
  const maxLength = 40
  const observaciones = props.pozo?.observaciones ?? ''
  return observaciones.length > maxLength ? observaciones.substring(0, maxLength) : observaciones || 'Aún no hay observaciones.'
})

const openModalFullText = () => {
  showFullMessage.value = true
}

const closeModalFullText = () => {
  showFullMessage.value = false
}
</script>

<template>
  <div>
    <Modal :show="showFullMessage" @close="closeModalFullText">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Observaciones</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModalFullText">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6" style="height: 150px">
          <p class="text-base text-gray-600">{{ pozo.observaciones }}</p>
        </div>
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200 rounded-b">
          <button class="btn-secondary" type="button" @click="closeModalFullText">Cerrar</button>
        </div>
      </div>
    </Modal>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-white border-b">
          <tr>
            <th scope="col" class="border p-1" colspan="2" />
            <th scope="col" class="border p-1" />
            <th scope="col" class="border p-1" />
            <th scope="col" class="border p-1" colspan="2" />
          </tr>
        </thead>
        <tbody>
          <tr class="bg-gray-50">
            <td class="border p-1" colspan="2" />
            <td class="border text-center p-1" colspan="4">
              <span class="block bg-gray-400 text-white font-bold leading-6">Datos del muestreo</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 text-white leading-6">Identificador</span>
            </td>
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 text-white leading-6">{{ pozo.id }}</span>
            </td>
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 text-white leading-6">Pozo/Instalación</span>
            </td>
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 text-white leading-6">Punto de muestreo</span>
            </td>
            <td class="text-center border p-1" colspan="2">
              <span class="block bg-yellow-400 text-white leading-6">Observaciones</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-left border whitespace-nowrap p-7 cursor-auto" colspan="2" rowspan="5">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400 mx-auto w-[14.063rem]">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <a class="flex flex-1 items-center text-yellow-600 hover:underline" href="#docpozos" title="Documento de Pozos" @click.prevent="activeTab('docpozos')">
                    <Icon class="h-4 w-4 flex-shrink-0 text-yellow-600" name="pencil" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Análisis</span>
                  </a>
                  <span class="text-yellow-600 font-semibold">({{ docPozos.length }})</span>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <a class="flex flex-1 items-center text-yellow-600 hover:underline" href="#componentepozos" title="Componentes de Pozo" @click.prevent="activeTab('componentepozos')">
                    <Icon class="h-4 w-4 flex-shrink-0 text-yellow-600" name="chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Componentes</span>
                  </a>
                  <span class="text-yellow-600 font-semibold">({{ componentePozos.length }})</span>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <a class="flex flex-1 items-center text-yellow-600 hover:underline" href="#cromatografiagas" title="Cromatografía de Gas" @click.prevent="activeTab('cromatografiagas')">
                    <Icon class="h-4 w-4 flex-shrink-0 text-yellow-600" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía Gas</span>
                  </a>
                  <span class="text-yellow-600 font-semibold">({{ cromatografiaGases.length }})</span>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <a class="flex flex-1 items-center text-yellow-600 hover:underline" href="#cromatografialiquida" title="Cromatografía Líquida" @click.prevent="activeTab('cromatografialiquida')">
                    <Icon class="h-4 w-4 flex-shrink-0 text-yellow-600" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate"> Cromatografía Líquida</span>
                  </a>
                  <span class="text-yellow-600 font-semibold">({{ cromatografiaLiquidas.length }})</span>
                </li>
              </ul>
            </td>
            <td class="text-center border p-1">
              <span class="block leading-6">{{ pozo.nombre_pozo }}</span>
            </td>
            <td class="text-center border p-1">
              <span class="block leading-6">{{ pozo.punto_muestreo }}</span>
            </td>
            <td class="text-left border p-1" colspan="2">
              <span class="block">
                {{ truncateMessage }}
                <a v-if="pozo.observaciones?.length > 40" class="text-yellow-400 hover:underline" href="#" @click.prevent="openModalFullText">Ver Más...</a>
              </span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border p-1" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Fecha del muestreo</span>
            </td>
            <td class="text-center border p-1" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Presión</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-center border p-1" colspan="2">
              <span class="block leading-6">{{ pozo.fecha_hora }}</span>
            </td>
            <td class="text-center border p-1" colspan="2">
              <span class="block leading-6">{{ pozo.presion_kgcm2 }} KM/CM2 {{ pozo.presion_psi }} PSIA</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border p-1" colspan="2">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Temperatura</span>
            </td>
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Volúmen</span>
            </td>
            <td class="text-center border p-1">
              <span class="block bg-yellow-400 font-bold text-white leading-6">Identificador</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="text-center border p-1" colspan="2">
              <span class="block leading-6">{{ pozo.temp_C }} °C {{ pozo.temp_F }} °F</span>
            </td>
            <td class="text-center border p-1">
              <span class="block leading-6">{{ pozo.volumen_cm3 }} CM3 {{ pozo.volumen_lts }} LTS</span>
            </td>
            <td class="text-center border p-1">
              <span class="block leading-6">{{ pozo.identificador === '' || pozo.identificador === null ? 'Sin identificador.' : pozo.identificador }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
