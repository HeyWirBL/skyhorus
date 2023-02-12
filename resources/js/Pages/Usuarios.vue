<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({ usuarios: Array })
</script>
<template>
  <div>
    <Head title="Usuarios" />
    <h1 class="mb-8 text-3xl font-bold">Usuarios</h1>
    <ul>
      <li v-for="usuario in usuarios" :key="usuario.idUsuario">{{ usuario.nombre }}</li>
    </ul>
  </div>
</template>
