<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PozoInfo from './Partials/PozoInfo.vue'

defineProps({
  can: Object,
  filters: Object,
  pozo: Object,
})
</script>

<template>
  <div>
    <Head :title="`${pozo.nombre_pozo}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ pozo.nombre_pozo }}
      </h1>
    </div>

    <!-- Pozo Table Component -->
    <PozoInfo :can="can" :filters="filters" :pozo="pozo" />
  </div>
</template>
