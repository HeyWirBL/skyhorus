<script setup>
import { computed, inject, ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const swal = inject('$swal')

const selected = ref([])
const selectAllComPozos = ref(false)

const componentePozoForm = useForm({})

const componentePozos = computed(() => props.pozo.componentePozos)

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
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Componentes de Pozo</h2>
      <a v-if="can.createComponentePozo" class="btn-yellow" href="#">
        <span>Importar</span>
        <span class="hidden md:inline">&nbsp;Excel</span>
      </a>
    </div>

    <div class="flex items-center mb-6">
      <button v-if="componentePozos.data.length !== 0 && can.deleteComponentePozo" class="btn-secondary" type="button" :disabled="!selectAllComPozos && !selected.length" @click="removeSelectedItems">
        <span>Borrar</span>
        <span class="hidden md:inline">&nbsp;Elementos Seleccionados</span>
      </button>
    </div>

    <div class="bg-white shadow rounded-md overflow-x-auto">
      <table class="w-full whitespace-nowrap text-sm">
        <thead class="bg-white border-b-2">
          <tr v-if="componentePozos.data.length !== 0">
            <th v-if="can.editComponentePozo" scope="col" class="p-4 w-4 border-solid border border-gray-200" />
            <th v-if="can.editComponentePozo" scope="col" class="p-4 border-solid border border-gray-200">
              <div class="flex items-center">
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
          <template v-for="compozo in componentePozos.data" :key="compozo.id">
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
              <td v-if="can.editComponentePozo" class="p-1 whitespace-nowrap border-solid border border-gray-200">
                <span class="inline-block whitespace-nowrap" title="Editar componente de pozo">
                  <Link class="flex items-center" :href="`/componente-pozos/${compozo.id}/editar`" tabindex="-1">
                    <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="pencil" />
                  </Link>
                </span>
                <span class="inline-block whitespace-nowrap ml-2" title="Ver componente de pozo">
                  <Link class="flex items-center" :href="`/componente-pozos/${compozo.id}`" tabindex="-1">
                    <Icon class="flex-shrink-0 w-4 h-4 fill-yellow-400" name="eye" />
                  </Link>
                </span>
              </td>
              <td class="w-4 p-4 border-solid border border-gray-200">
                <div class="flex items-center">
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
                  <a class="text-yellow-400 hover:underline" href="#" @click="openModalMessageMet">Ver Más...</a>
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
                <span class="block">{{ compozo.observaciones.substring(0, 38) }} <a class="text-yellow-400 hover:underline" href="#" @click="openModalObs">Ver Más...</a></span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                <span class="block">43</span>
              </td>
              <td class="p-1 text-center leading-6 border-solid border border-gray-200">
                <span class="block">54</span>
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
                      <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                      <span class="ml-2 w-0 flex-1 truncate">Documento PDF</span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                      <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500" type="button" @click.prevent="download">Descargar</Link>
                    </div>
                  </li>
                  <li class="flex items-center justify-between py-3 pl-3 pr-4">
                    <div class="flex w-0 flex-1 items-center">
                      <Icon class="h-4 w-4 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                      <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL ({{ compozo.quimicosData.length }})</span>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                      <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" @click="openModalChart">Visualizar</a>
                    </div>
                  </li>
                </ul>
              </td>
            </tr>
          </template>
          <template v-if="componentePozos.data.length === 0">
            <tr class="bg-white">
              <td class="px-6 py-4 text-left leading-6 border-solid border border-gray-200" colspan="5">No se encontraron componentes registrados en este pozo.</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="componentePozos.links" :total="componentePozos.total" />
  </div>
</template>
