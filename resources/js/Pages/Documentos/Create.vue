<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>

<template>
  <div>
    <Head title="Subida de Documentos" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/documentos">Documentos</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Subir
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <p>Subida de archivos</p>
        </div>
      </form>
    </div>
  </div>
</template>
