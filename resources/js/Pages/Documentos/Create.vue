<script setup>
import { Head, Link } from '@inertiajs/vue3'
import DropZone from '@/Components/DropZone.vue'
</script>

<template>
  <div>
    <Head title="Subida de Documentos" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/documentos">Documentos</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Subir
    </h1>
    <div class="mb-4">
      <p class="text-sm text-gray-900">
        <span class="font-semibold">Tipos de Archivo:</span>
        <span>&nbsp;Todos los Archivos</span>
      </p>
    </div>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!-- DropZone -->
          <DropZone class="pb-8 pr-6 w-full" />
        </div>
      </form>
    </div>
  </div>
</template>
