<script setup>
import MainMenu from '@/Shared/MainMenu.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
  laravelVersion: String,
  phpVersion: String,
})
</script>
<template>
  <Head title="Welcome" />
  <h1>Home</h1>
  <MainMenu />
  <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
</template>
