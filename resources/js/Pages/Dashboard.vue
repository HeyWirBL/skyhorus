<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({ user: Object })
</script>
<template>
  <Head title="Dashboard" />
  <h1>Dashboard</h1>
  <p>Hello {{ user.name }}, welcome to your first Inertia app!</p>
</template>
