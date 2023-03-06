<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const elements = ref([
  {
    id: 1,
    name: 'Usuarios',
    href: '/users',
    description: 'Catálogo para registrar usuarios nuevos en el sistema y asignar respectivos roles.',
    icon: 'users',
  },
  {
    id: 2,
    name: 'Directorios',
    href: '/directorios',
    description: 'Catálogo de directorios para almacenar años y documentos.',
    icon: 'folders',
  },
  {
    id: 3,
    name: 'Pozos',
    href: '/pozos',
    description: 'Catálogo para la administración de pozos donde se podrá visualizar todo los detalles.',
    icon: 'office',
  },
  {
    id: 4,
    name: 'Documentos de Pozos',
    href: '/doc-pozos',
    description: 'Catálogo de documentos asignados a cada uno de los pozos.',
    icon: 'documents',
  },
  {
    id: 5,
    name: 'Componente de Pozos',
    href: '/componente-pozos',
    description: 'Catálogo para administración y asignación de valores a los pozos.',
    icon: 'components',
  },
  {
    id: 6,
    name: 'Cromatografía de gas',
    href: '/cromatografia-gases',
    description: 'Catálogo de documentos para adjuntar archivos PDF en el apartado de cromatografía de gas.',
    icon: 'fire',
  },
  {
    id: 7,
    name: 'Cromatografía líquida',
    href: '/cromatografia-liquidas',
    description: 'Catálogo de documentos para adjuntar archivos PDF en el apartado de cromatografía líquida.',
    icon: 'liquid',
  },
  {
    id: 8,
    name: 'Años',
    href: '/anos',
    description: 'Catálogo de años asignados a cada una de las carpetas creadas.',
    icon: 'calendar',
  },
  {
    id: 9,
    name: 'Documentos',
    href: '/documentos',
    description: 'Catálogo de documentos para asignación de los mismos.',
    icon: 'documents',
  },
])
</script>

<template>
  <div>
    <Head title="Menú" />
    <h1 class="mb-8 text-3xl font-bold">Menú</h1>
    <div class="flex items-center justify-between mb-6">
      <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
        <Link v-for="(element, index) in elements" :key="index" :href="element.href" class="block p-4 border border-gray-200 rounded shadow hover:bg-gray-200">
          <h3 class="flex items-center text-lg font-semibold tracking-tight text-gray-900 leading-snug">
            <Icon class="w-4 h-4 fill-yellow-400" :name="element.icon" aria-hidden="true" />
            <span class="ml-2">{{ element.name }}</span>
          </h3>
          <p class="mt-1 text-sm font-normal text-gray-900">{{ element.description }}</p>
        </Link>
      </div>
    </div>
  </div>
</template>
