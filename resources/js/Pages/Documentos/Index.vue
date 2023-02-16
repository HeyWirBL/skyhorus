<script setup>
import { Head, Link } from '@inertiajs/vue3'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'
</script>

<template>
  <div>
    <Head title="Documentos" />
    <h1 class="mb-8 text-3xl font-bold">Documentos</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter class="mr-4 w-full max-w-md">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow" href="/documentos/crear">
        <span>Subir</span>
        <span class="hidden md:inline">&nbsp;Documentos</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th>wd</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</template>
