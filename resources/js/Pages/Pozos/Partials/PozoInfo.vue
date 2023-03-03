<script setup>
import { inject, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
/* Tables */
import PozoTable from './Tables/PozoTable.vue'
import DocPozoTable from './Tables/DocPozoTable.vue'
import ComPozoTable from './Tables/ComPozoTable.vue'
import CromGasTable from './Tables/CromGasTable.vue'
import CromLiquidaTable from './Tables/CromLiquidaTable.vue'

const props = defineProps({
  can: Object,
  pozo: Object,
})

const swal = inject('$swal')

// State of Tabs Links
const activeTab = ref('')

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
</script>

<template>
  <div>
    <TrashedMessage v-if="can.restorePozo && pozo.deleted_at" class="mb-6" @restore="restore">Este pozo ha sido eliminado.</TrashedMessage>

    <!-- Pozo Table -->
    <PozoTable :can="can" :pozo="pozo" @active="activeTab = $event" />

    <!-- Document Analysis -->
    <div v-show="activeTab === 'docpozos'" id="docpozos" class="mt-12">
      <!-- DocPozoTable Component -->
      <DocPozoTable :can="can" :pozo="pozo" />
    </div>

    <!-- Pozo Components -->
    <div v-show="activeTab === 'componentepozos'" id="componentepozos" class="mt-12">
      <!-- ComPozoTable Component -->
      <ComPozoTable :can="can" :pozo="pozo" />
    </div>

    <!-- Pozo Gas Documents -->
    <div v-show="activeTab === 'cromatografiagas'" id="cromatografiagas" class="mt-12">
      <!-- CromGasTable Component -->
      <CromGasTable :pozo="pozo" />
    </div>

    <!-- Pozo Liquid Documents -->
    <div v-show="activeTab === 'cromatografialiquida'" id="cromatografialiquida" class="mt-12">
      <!-- CromLiquidaTable Component -->
      <CromLiquidaTable :pozo="pozo" />
    </div>
  </div>
</template>
