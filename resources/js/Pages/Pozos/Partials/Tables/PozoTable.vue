<script setup>
import { computed, inject } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const swal = inject('$swal')

const emit = defineEmits(['active'])

// Computed collections
const docPozos = computed(() => props.pozo.docPozos.data)
const componentePozos = computed(() => props.pozo.componentePozos.data)
const cromatografiaGases = computed(() => props.pozo.cromatografiaGases.data)
const cromatografiaLiquidas = computed(() => props.pozo.cromatografiaLiquidas.data)

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

const activeTab = (tabName) => {
  emit('active', tabName)
}

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
</script>

<template>
  <div>
    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="bg-white border-b">
          <tr>
            <th scope="col" class="border px-6 py-4" colspan="2">
              <button v-if="can.deletePozo && !pozo.deleted_at" class="text-red-600 font-medium hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Pozo</button>
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
                    <span class="ml-2 w-0 flex-1 truncate">Análisis ({{ docPozos.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#docpozos" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab('docpozos')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Componentes ({{ componentePozos.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#componentepozos" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab('componentepozos')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía de Gas ({{ cromatografiaGases.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#cromatografiagas" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab('cromatografiagas')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate"> Cromatografía Líquida ({{ cromatografiaLiquidas.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#cromatografialiquida" class="font-medium text-yellow-600 hover:text-yellow-500" @click.prevent="activeTab('cromatografialiquida')">Mostrar</a>
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
  </div>
</template>
