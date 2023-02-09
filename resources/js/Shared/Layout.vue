<script setup>
import { Link } from '@inertiajs/vue3'
import MainMenu from '@/Shared/MainMenu.vue'
</script>

<template>
  <div>
    <div class="md:flex md:flex-col">
      <div class="md:flex md:flex-col md:h-screen">
        <div class="md:flex md:flex-shrink-0">
          <div class="flex items-center justify-between px-6 py-4 bg-yellow-900 md:flex-shrink-0 md:justify-center md:w-56">
            <Link class="mt-1" href="/"> GestionDocumental </Link>
          </div>
          <div class="md:flex md:flex-grow md:overflow-hidden">
            <MainMenu class="hidden flex-shrink-0 p-12 w-56 bg-yellow-800 overflow-y-auto md:block" />
            <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
              <slot />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
