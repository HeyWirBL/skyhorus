<template>
  <div>
    <Head :title="`${pozo.nombre_pozo}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ pozo.nombre_pozo }}
      </h1>
      <button class="btn-yellow ml-auto" @click="openEditModal">
        <span>Editar</span>
        <span class="hidden md:inline">&nbsp;Pozo</span>
      </button>
    </div>

    <!-- Edit Pozo Modal Component -->
    <EditPozoModal :pozo="pozo" :show="editPozoModal" @close="editPozoModal = false" />

    <!-- Pozo Table Component -->
    <PozoTableInfo :pozo="pozo" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import EditPozoModal from './Partials/EditPozoModal.vue'
import PozoTableInfo from './Partials/PozoTableInfo.vue'

defineProps({
  pozo: Object,
})

const editPozoModal = ref(false)

const openEditModal = () => {
  editPozoModal.value = true
}
</script>
