<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({ user: Object })
</script>
<template>
  <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">Hello {{ user.name }}, welcome to your first Inertia app!</p>
  </div>
</template>
