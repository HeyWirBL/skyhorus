<script setup>
import { computed, reactive, watchEffect } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import SelectInput from '@/Components/SelectInput.vue'
import TextInput from '@/Components/TextInput.vue'
import CopyPasteTextInput from './Partials/CopyPasteTextInput.vue'

defineProps({
  pozos: Array,
})

const comPozoForm = useForm({
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
  fecha_recep: '',
  pozo_id: '',
  fecha_analisis: '',
  no_determinacion: '',
  equipo_utilizado: '',
  met_laboratorio: '',
  observaciones: null,
  nombre_componente: '',
  fecha_muestreo: null,
})

const state = reactive({
  textarea: '',
  rows: [],
  columns: ['Col 1', 'Col 2', 'Col 3', 'Col 4', 'Col 5', 'Col 6', 'Col 7', 'Col 8', 'Col 9', 'Col 10', 'Col 11', 'Col 12', 'Col 13', 'Col 14', 'Col 15', 'Col 16', 'Col 17', 'Col 18', 'Col 19', 'Col 20', 'Col 21', 'Col 22', 'Col 23', 'Col 24', 'Col 25', 'Col 26', 'Col 27', 'Col 28', 'Col 29', 'Col 30', 'Col 31', 'Col 32', 'Col 33', 'Col 34', 'Col 35', 'Col 36', 'Col 37', 'Col 38', 'Col 39', 'Col 40', 'Col 41', 'Col 42', 'Col 43', 'Col 44', 'Col 45', 'Col 46', 'Col 47', 'Col 48', 'Col 49'],
  comPozoFields: [
    { id: 0, value: 'dioxido_carbono', name: 'Dióxido de carbono - PM' },
    { id: 1, value: 'pe_dioxido_carbono', name: 'Dióxido de carbono - % Peso' },
    { id: 2, value: 'mo_dioxido_carbono', name: 'Dióxido de carbono - % MOL' },
    { id: 3, value: 'den_dioxido_carbono', name: 'Dióxido de carbono - Densidad' },
    { id: 4, value: 'acido_sulfidrico', name: 'Acido sulfhídrico - PM' },
    { id: 5, value: 'pe_acido_sulfidrico', name: 'Acido Sulfhídrico - % Peso' },
    { id: 6, value: 'mo_acido_sulfidrico', name: 'Acido Sulfhídrico - % MOL' },
    { id: 7, value: 'den_acido_sulfidrico', name: 'Acido Sulfhídrico - Densidad' },
    { id: 8, value: 'nitrogeno', name: 'Nitrógeno - PM' },
    { id: 9, value: 'pe_nitrogeno', name: 'Nitrógeno - % Peso' },
    { id: 10, value: 'mo_nitrogeno', name: 'Nitrógeno - % MOL' },
    { id: 11, value: 'den_nitrogeno', name: 'Nitrógeno - Densidad' },
    { id: 12, value: 'metano', name: 'Metano - PM' },
    { id: 13, value: 'pe_metano', name: 'Metano - % Peso' },
    { id: 14, value: 'mo_metano', name: 'Metano - % MOL' },
    { id: 15, value: 'den_metano', name: 'Metano - Densidad' },
    { id: 16, value: 'etano', name: 'Etano - PM' },
    { id: 17, value: 'pe_etano', name: 'Etano - % Peso' },
    { id: 18, value: 'mo_etano', name: 'Etano - % MOL' },
    { id: 19, value: 'den_etano', name: 'Etano - Densidad' },
    { id: 20, value: 'propano', name: 'Propano - PM' },
    { id: 21, value: 'pe_propano', name: 'Propano - % Peso' },
    { id: 22, value: 'mo_propano', name: 'Propano - % MOL' },
    { id: 23, value: 'den_propano', name: 'Propano - Densidad' },
    { id: 24, value: 'iso_butano', name: 'Iso-Butano - PM' },
    { id: 25, value: 'pe_iso_butano', name: 'Iso-Butano - % Peso' },
    { id: 26, value: 'mo_iso_butano', name: 'Iso-Butano - % MOL' },
    { id: 27, value: 'den_iso_butano', name: 'Iso-Butano - Densidad' },
    { id: 28, value: 'n_butano', name: 'n-Butano - PM' },
    { id: 29, value: 'pe_n_butano', name: 'n-Butano - % Peso' },
    { id: 30, value: 'mo_n_butano', name: 'n-Butano - % MOL' },
    { id: 31, value: 'den_n_butano', name: 'n-Butano - Densidad' },
    { id: 32, value: 'iso_pentano', name: 'Iso-Pentano - PM' },
    { id: 33, value: 'pe_iso_pentano', name: 'Iso-Pentano - % Peso' },
    { id: 34, value: 'mo_iso_pentano', name: 'Iso-Pentano - % MOL' },
    { id: 35, value: 'den_iso_pentano', name: 'Iso-Pentano - Densidad' },
    { id: 36, value: 'n_pentano', name: 'n-Pentano - PM' },
    { id: 37, value: 'pe_n_pentano', name: 'n-Pentano - % Peso' },
    { id: 38, value: 'mo_n_pentano', name: 'n-Pentano - % MOL' },
    { id: 39, value: 'den_n_pentano', name: 'n-Pentano - Densidad' },
    { id: 40, value: 'n_exano', name: '**n-Exano - PM' },
    { id: 41, value: 'pe_n_exano', name: '**n-Exano - % Peso' },
    { id: 42, value: 'mo_n_exano', name: '**n-Exano - % MOL' },
    { id: 43, value: 'den_n_exano', name: '**n-Exano - Densidad' },
    { id: 44, value: 'no_determinacion', name: 'Número de determinación' },
    { id: 45, value: 'equipo_utilizado', name: 'Equipo utilizado' },
    { id: 46, value: 'met_laboratorio', name: 'Método de laboratorio' },
    { id: 47, value: 'observaciones', name: 'Observaciones' },
    { id: 48, value: 'nombre_componente', name: 'Nombre del grupo de componentes' },
  ],
})

const validationMessage = computed(() => {
  const numValues = state.textarea.split(',').map((value) => value.trim()).length
  const numColumns = state.columns.length

  if (numValues % numColumns !== 0) {
    return `Por favor introduzca ${numColumns} valores separados por comas.`
  }

  return ''
})

watchEffect(() => {
  const values = state.textarea.split(',').map((value) => value.trim())

  if (values.length % state.columns.length !== 0) {
    return
  }
  state.rows = []

  for (let i = 0; i < values.length; i += state.columns.length) {
    const row = {}
    for (let j = 0; j < state.columns.length; j++) {
      row[state.columns[j]] = values[i + j]
    }
    state.rows.push(row)
  }
  //showTable.value = true
})
</script>

<template>
  <div>
    <Head title="Importar Componentes de Pozo" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes de Pozo</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Importar
    </h1>

    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden mb-6">
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <SelectInput class="pb-8 pr-6 w-full lg:w-1/2" label="Selecciona el Pozo/Instalación">
          <option value="" />
          <option v-for="pozo in pozos" :key="pozo.id" :value="pozo.id">{{ pozo.nombre_pozo }}</option>
        </SelectInput>
        <TextInput class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de Recepción" />
        <TextInput class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de Análisis" />
        <TextInput class="pb-8 pr-6 w-full lg:w-1/2" type="date" label="Fecha de Muestreo" />
        <CopyPasteTextInput v-model.trim="state.textarea" class="pb-8 pr-6 w-full" label="Copie y pegue su texto aquí separado por comas" />
        <div v-if="validationMessage" class="pb-8">
          <span class="font-semibold"> Sugerencia: </span>
          <span class="text-red-500">{{ validationMessage }}</span>
        </div>
      </div>
    </div>

    <div class="w-full bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <th v-for="(column, columnIndex) in state.columns" :key="columnIndex" style="width: 272px; min-width: 272px; max-width: 272px">
            <SelectInput class="w-full">
              <option value="" />
              <option v-for="compozof in state.comPozoFields" :key="compozof.id" :value="compozof.value">{{ compozof.name }}</option>
            </SelectInput>
          </th>
          <th />
        </thead>
        <tbody>
          <tr v-for="(row, rowIndex) in state.rows" :key="rowIndex">
            <td v-for="(column, columnIndex) in state.columns" :key="columnIndex">{{ row[column] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
