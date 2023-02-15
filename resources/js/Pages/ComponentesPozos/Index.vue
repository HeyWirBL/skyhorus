<script setup>
import { Head, Link } from '@inertiajs/vue3'
import SearchFilter from '@/Components/SearchFilter.vue'
</script>

<template>
  <Head title="Componentes de Pozos" />
  <h1 class="mb-8 text-3xl font-bold">Componentes de Pozos</h1>
  <div class="flex items-center justify-between mb-6">
    <SearchFilter class="mr-4 w-full max-w-md">
      <label class="block mt-4 text-gray-700">Eliminado:</label>
      <select class="form-select mt-1 w-full">
        <option :value="null" />
        <option value="with">Con Modificación</option>
        <option value="only">Solo Eliminado</option>
      </select>
    </SearchFilter>
    <Link class="btn-yellow mr-4" href="/pozos/crear">
      <span>Subir</span>
      <span class="hidden md:inline">&nbsp;Documentos</span>
    </Link>
  </div>
</template>
