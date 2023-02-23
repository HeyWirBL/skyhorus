<script setup>
import { inject, nextTick, ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import LoadingButton from '@/Components/LoadingButton.vue'
import Modal from '@/Components/Modal.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TextInput from '@/Components/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

const props = defineProps({
  pozo: Object,
})

const swal = inject('$swal')

const editPozoModal = ref(false)
const firstInput = ref(null)
const tabSelect = ref('')
const selected = ref([])
const selectAllDocPozos = ref(false)
const selectAllComponentePozos = ref(false)
const selectAllCromatografiaGases = ref(false)
const selectAllCromatografiaLiquidas = ref(false)

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

const makeActive = (value) => {
  tabSelect.value = value
}

const isActiveTab = (value) => {
  return tabSelect.value === value
}

const openModal = () => {
  editPozoModal.value = true

  nextTick(() => firstInput.value.focus())
}

const updatePozo = () => {
  form.post(`/pozos/${props.pozo.id}`, {
    preserveScroll: true,
    onSuccess: () => (editPozoModal.value = false),
    onError: () => firstInput.value.focus(),
    onFinish: () => {
      if (!form.hasErrors) {
        form.reset()
      }
    },
  })
}

const closeModal = () => {
  editPozoModal.value = false
  form.reset()
}

const selectDocPozos = () => {
  selected.value = []
  if (!selectAllDocPozos.value) {
    for (let i in props.pozo.docPozos) {
      selected.value.push(props.pozo.docPozos[i].id)
    }
  }
}

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer este pozo?',
    text: 'Este pozo se restablecerá del modo "Solo Eliminado" y pasará al estado "Con Modificación".',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/pozos/${props.pozo.id}/restore`)
    }
  })
}

const selectComponentePozos = () => {
  selected.value = []
  if (!selectAllComponentePozos.value) {
    for (let i in props.pozo.componentePozos) {
      selected.value.push(props.pozo.componentePozos[i].id)
    }
  }
}

const selectCromatografiaGases = () => {
  selected.value = []
  if (!selectAllCromatografiaGases.value) {
    for (let i in props.pozo.cromatografiaGases) {
      selected.value.push(props.pozo.cromatografiaGases[i].id)
    }
  }
}

const selectCromatografiaLiquidas = () => {
  selected.value = []
  if (!selectAllCromatografiaLiquidas.value) {
    for (let i in props.pozo.cromatografiaLiquidas) {
      selected.value.push(props.pozo.cromatografiaLiquidas[i].id)
    }
  }
}
</script>

<template>
  <div>
    <Head :title="`${pozo.nombre_pozo}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ pozo.nombre_pozo }}
      </h1>
      <button class="btn-yellow ml-auto" @click="openModal">
        <span>Editar</span>
        <span class="hidden md:inline">&nbsp;Pozo</span>
      </button>
    </div>

    <TrashedMessage v-if="pozo.deleted_at" class="mb-6" @restore="restore">Este pozo ha sido eliminado.</TrashedMessage>

    <Modal :show="editPozoModal" style="max-width: 900px">
      <div class="relative">
        <!-- Modal Header -->
        <div class="flex items-start justify-between p-4 border-b rounded-t">
          <h2 class="text-xl font-semibold">Pozo, Editar [{{ pozo.id }}]</h2>

          <button class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-700 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" type="button" @click="closeModal">
            <Icon class="w-4 h-4" name="close" aria-hidden="true" />
            <span class="sr-only">Cerrar modal</span>
          </button>
        </div>
        <!-- Modal Body -->
        <div class="p-6 space-y-6">
          <form @submit.prevent="updatePozo">
            <div class="flex flex-wrap text-sm leading-relaxed">
              <TextInput ref="firstInput" v-model="form.nombre_pozo" :error="form.errors.nombre_pozo" class="pb-4 pr-6 w-full lg:w-1/2" label="Pozo o Instalación" />
              <TextInput v-model="form.temp_C" :error="form.errors.temp_C" class="pb-4 pr-6 w-full lg:w-1/2" label="°C" />
              <TextInput v-model="form.punto_muestreo" :error="form.errors.punto_muestreo" class="pb-4 pr-6 w-full lg:w-1/2" label="Punto de muestreo" />
              <TextInput v-model="form.temp_F" :error="form.errors.temp_F" class="pb-4 pr-6 w-full lg:w-1/2" label="°F" />
              <TextInput v-model="form.fecha_hora" :error="form.errors.fecha_hora" class="pb-4 pr-6 w-full lg:w-1/2" label="Fecha" type="date" />
              <TextInput v-model="form.volumen_cm3" :error="form.errors.volumen_cm3" class="pb-4 pr-6 w-full lg:w-1/2" label="CM3" />
              <TextInput v-model="form.identificador" :error="form.errors.identificador" class="pb-4 pr-6 w-full lg:w-1/2" label="Identificador" />
              <TextInput v-model="form.volumen_lts" :error="form.errors.volumen_lts" class="pb-4 pr-6 w-full lg:w-1/2" label="Volumen LTS" />
              <TextInput v-model="form.presion_kgcm2" :error="form.errors.presion_kgcm2" class="pb-4 pr-6 w-full lg:w-1/2" label="KG/CM2" />
              <TextInput v-model="form.presion_psi" :error="form.errors.presion_psi" class="pb-4 pr-6 w-full lg:w-1/2" label="PSIA" />
              <TextareaInput v-model="form.observaciones" :error="form.errors.observaciones" class="pb-4 pr-6 w-full" label="Observaciones" placeholder="Ingresar observaciones adicionales" />
            </div>
            <div class="mt-6 flex justify-end">
              <button class="btn-secondary" type="button" @click="closeModal">Cancelar</button>
              <LoadingButton :loading="form.processing" class="btn-yellow ml-3" type="submit">Guardar</LoadingButton>
            </div>
          </form>
        </div>
      </div>
    </Modal>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="border px-6 py-4" colspan="2" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" />
            <th scope="col" class="border px-6 py-4" colspan="2" />
          </tr>
        </thead>
        <tbody>
          <tr class="bg-gray-50">
            <td class="border px-6 py-4" colspan="2" />
            <td class="border text-center px-6 py-4 align-top" colspan="4">
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
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" scroll-region @click.prevent="makeActive('docPozos')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Componentes ({{ pozo.componentePozos.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" scroll-region @click.prevent="makeActive('componentePozos')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía de Gas ({{ pozo.cromatografiaGases.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" scroll-region @click.prevent="makeActive('cromatografiaGases')">Mostrar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex flex-1 items-center">
                    <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="document-plus" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate"> Cromatografía Líquida ({{ pozo.cromatografiaLiquidas.length }})</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" scroll-region @click.prevent="makeActive('cromatografiaLiquidas')">Mostrar</a>
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
              <span class="block">{{ pozo.observaciones === '' ? 'Aún no hay observaciones.' : pozo.observaciones }}</span>
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
              <span class="block leading-6">{{ pozo.identificador === '' ? 'Sin identificador.' : pozo.identificador }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Análisis de Documentos -->
    <div v-show="isActiveTab('docPozos')" class="mt-12">
      <h2 class="text-2xl font-bold">Documentos del Pozo</h2>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead class="text-sm text-left font-bold uppercase bg-white border-b">
            <tr>
              <th scope="col" class="p-4">
                <div class="flex items-center">
                  <input id="checkbox-all-docpozos" v-model="selectAllDocPozos" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="selectDocPozos" />
                  <label for="checkbox-all-docpozos" class="sr-only">checkbox</label>
                </div>
              </th>
              <th scope="col" class="px-6 py-3">Documento</th>
              <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="docPozo in pozo.docPozos" :key="docPozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
              <td class="w-4 p-4">
                <div class="flex items-center">
                  <input :id="`checkbox-docpozo-${docPozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="docPozo.id" />
                  <label :for="`checkbox-docpozo-${docPozo.id}`" class="sr-only">checkbox</label>
                </div>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${docPozo.id}`">
                  {{ docPozo.documento }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${docPozo.id}`" tabindex="-1">{{ docPozo.fecha_hora }} </Link>
              </td>
              <td class="w-px">
                <Link class="flex items-center px-6" :href="`/docpozos/${docPozo.id}`" tabindex="-1">
                  <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
                </Link>
              </td>
            </tr>
            <tr v-if="pozo.docPozos.length === 0">
              <td class="px-6 py-4" colspan="5">No se encontraron documentos del pozo registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Componentes de Pozo -->
    <div v-show="isActiveTab('componentePozos')" class="mt-12">
      <h2 class="text-2xl font-bold">Componentes del Pozo</h2>
      <div class="mt-6 bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead class="text-sm text-left font-bold uppercase bg-white border-b">
            <tr>
              <th scope="col" class="p-4">
                <div class="flex items-center">
                  <input id="checkbox-all-componentepozos" v-model="selectAllComponentePozos" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="selectComponentePozos" />
                  <label for="checkbox-all-componentepozos" class="sr-only">checkbox</label>
                </div>
              </th>
              <th scope="col" class="px-6 py-3">No.</th>
              <th scope="col" class="px-6 py-3">Nombre del Componente</th>
              <th scope="col" class="px-6 py-3">Equipo Utilizado</th>
              <th scope="col" class="px-6 py-3" colspan="2">Fecha de Recepción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="componentePozo in pozo.componentePozos" :key="componentePozo.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
              <td class="w-4 p-4">
                <div class="flex items-center">
                  <input :id="`checkbox-componentespozo-${componentePozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="componentePozo.id" />
                  <label :for="`checkbox-componentespozo-${componentePozo.id}`" class="sr-only">checkbox</label>
                </div>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`">
                  {{ componentePozo.id }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                  {{ componentePozo.nombre_componente }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                  {{ componentePozo.equipo_utilizado }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                  {{ componentePozo.fecha_recep }}
                </Link>
              </td>
              <td class="w-px">
                <Link class="flex items-center px-6" :href="`/componente-pozos/${componentePozo.id}`" tabindex="-1">
                  <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
                </Link>
              </td>
            </tr>
            <tr v-if="pozo.componentePozos.length === 0">
              <td class="px-6 py-4" colspan="5">No se encontraron componentes del pozo registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Cromatografia de Gases -->
    <div v-show="isActiveTab('cromatografiaGases')" class="mt-12">
      <h2 class="text-2xl font-bold">Cromatografias de Gas</h2>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead class="text-sm text-left font-bold uppercase bg-white border-b">
            <tr>
              <th scope="col" class="p-4">
                <div class="flex items-center">
                  <input id="checkbox-all-cromatografiagases" v-model="selectAllCromatografiaGases" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="selectCromatografiaGases" />
                  <label for="checkbox-all-cromatografiagases" class="sr-only">checkbox</label>
                </div>
              </th>
              <th scope="col" class="px-6 py-3">Documento</th>
              <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cromatografiaGas in pozo.cromatografiaGases" :key="cromatografiaGas.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
              <td class="w-4 p-4">
                <div class="flex items-center">
                  <input :id="`checkbox-cromatografiagas-${cromatografiaGas.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaGas.id" />
                  <label :for="`checkbox-cromatografiagas-${cromatografiaGas.id}`" class="sr-only">checkbox</label>
                </div>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${cromatografiaGas.id}`">
                  {{ cromatografiaGas.documento }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${cromatografiaGas.id}`" tabindex="-1">{{ cromatografiaGas.fecha_hora }} </Link>
              </td>
              <td class="w-px">
                <Link class="flex items-center px-6" :href="`/docpozos/${cromatografiaGas.id}`" tabindex="-1">
                  <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
                </Link>
              </td>
            </tr>
            <tr v-if="pozo.cromatografiaGases.length === 0">
              <td class="px-6 py-4" colspan="5">No se encontraron cromatografias de gas registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Cromatografia Líquidas -->
    <div v-show="isActiveTab('cromatografiaLiquidas')" class="mt-12">
      <h2 class="text-2xl font-bold">Cromatografias Líquidas</h2>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead class="text-sm text-left font-bold uppercase bg-white border-b">
            <tr>
              <th scope="col" class="p-4">
                <div class="flex items-center">
                  <input id="checkbox-all-cromatografialiquidas" v-model="selectAllCromatografiaLiquidas" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="selectCromatografiaLiquidas" />
                  <label for="checkbox-all-cromatografialiquidas" class="sr-only">checkbox</label>
                </div>
              </th>
              <th scope="col" class="px-6 py-3">Documento</th>
              <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cromatografiaLiquida in pozo.cromatografiaLiquidas" :key="cromatografiaLiquida.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
              <td class="w-4 p-4">
                <div class="flex items-center">
                  <input :id="`checkbox-cromatografialiquida-${cromatografiaLiquida.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromatografiaLiquida.id" />
                  <label :for="`checkbox-cromatografialiquida-${cromatografiaLiquida.id}`" class="sr-only">checkbox</label>
                </div>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${cromatografiaLiquida.id}`">
                  {{ cromatografiaLiquida.documento }}
                </Link>
              </td>
              <td>
                <Link class="flex items-center px-6 py-4" :href="`/docpozos/${cromatografiaLiquida.id}`" tabindex="-1">{{ cromatografiaLiquida.fecha_hora }} </Link>
              </td>
              <td class="w-px">
                <Link class="flex items-center px-6" :href="`/docpozos/${cromatografiaLiquida.id}`" tabindex="-1">
                  <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
                </Link>
              </td>
            </tr>
            <tr v-if="pozo.cromatografiaLiquidas.length === 0">
              <td class="px-6 py-4" colspan="5">No se encontraron cromatografias liquidas registradas.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
