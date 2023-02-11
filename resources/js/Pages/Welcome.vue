<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  laravelVersion: String,
  phpVersion: String,
})
</script>

<template>
  <Head title="Welcome" />
  <h1 class="text-4xl font-bold">Home</h1>
  <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
</template>
