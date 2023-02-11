<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <div class="text-yellow-300 group-hover:text-white">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/usuarios">
        <div class="text-yellow-300 group-hover:text-white">Usuarios</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/directorios">
        <div class="text-yellow-300 group-hover:text-white">Carpetas</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/meses">
        <div class="text-yellow-300 group-hover:text-white">Meses</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/anos">
        <div class="text-yellow-300 group-hover:text-white">Años</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/pozos">
        <div class="text-yellow-300 group-hover:text-white">Pozos</div>
      </Link>
    </div>
  </div>
</template>
