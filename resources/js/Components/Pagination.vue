<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({ links: Array, total: Number })
</script>

<template>
  <div v-if="links.length > 3">
    <div class="flex items-center justify-between">
      <span class="text-sm font-normal text-gray-500">
        Mostrando <span class="font-semibold text-gray-900">1-{{ total }}</span> de <span class="font-semibold text-gray-900">{{ total }}</span>
      </span>
      <div class="inline-flex items-center">
        <template v-for="(link, key) in links">
          <div v-if="link.url === null" :key="key" class="mr-1 px-3 py-2 leading-tight text-gray-400 border rounded" v-html="link.label" />
          <Link v-else :key="`link-${key}`" :href="link.url" class="mr-1 px-3 py-2 leading-tight text-gray-500 focus:text-yellow-500 bg-white border focus:border-yellow-500 hover:bg-white hover:text-gray-700 rounded" :class="{ 'bg-white': link.active }" v-html="link.label" />
        </template>
      </div>
    </div>
  </div>
</template>
