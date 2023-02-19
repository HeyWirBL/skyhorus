<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  pozos: Array,
})

const form = useForm({})

const store = () => {
  form.post('/componente-pozos')
}
</script>

<template>
  <div>
    <Head title="Importar Componentes Pozo" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes de Pozo</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> Importar
    </h1>
    <div class="mb-4">
      <p class="text-sm text-gray-900">
        <span class="font-semibold">Sugerencia:</span>
        <span>&nbsp;Importar archivo separado por comas (.csv), Excel (.xlsx)</span>
      </p>
    </div>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!-- DropZone -->
          wfs
        </div>
      </form>
    </div>
  </div>
</template>
