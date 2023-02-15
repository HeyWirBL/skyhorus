<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  pozo: Object,
})
</script>

<template>
  <Head :title="pozo.nombre_pozo" />
  <h1>Pozo</h1>
</template>
