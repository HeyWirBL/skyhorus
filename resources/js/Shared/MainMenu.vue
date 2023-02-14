<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const currentUrl = computed(() => usePage().url.substring(1))

const isUrl = (...urls) => {
  if (urls[0] === '') {
    return currentUrl.value === ''
  }
  return urls.filter((url) => currentUrl.value.startsWith(url)).length
}
</script>

<template>
  <div>
    <!-- Dashboard Link -->
    <div class="mb-4">
      <Link class="group flex text-base items-center p-2 rounded-md" :class="isUrl('') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/">
        <Icon class="w-5 h-5" :class="isUrl('') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="dashboard" />
        <div class="ml-2" :class="isUrl('') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <!-- End Dashboard Link -->

    <!-- General Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="general-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-6 h-6" name="rectangle-group" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Generales</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="general-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Users Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('usuarios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/usuarios">
            <Icon class="w-5 h-5" :class="isUrl('usuarios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="users" />
            <div class="ml-2" :class="isUrl('usuarios') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Usuarios</div>
          </Link>
        </div>
        <!-- End Users Catalog -->

        <!-- Folders Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('directorios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/directorios">
            <Icon class="w-6 h-6" :class="isUrl('directorios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="folders" />
            <div class="ml-2" :class="isUrl('directorios') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Carpetas</div>
          </Link>
        </div>
        <!-- End Folders Catalog -->

        <!-- Months Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('meses') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/meses">
            <Icon class="w-6 h-6" :class="isUrl('meses') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="time" />
            <div class="ml-2" :class="isUrl('meses') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Meses</div>
          </Link>
        </div>
        <!-- End Months Catalog -->

        <!-- Years Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('anos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/anos">
            <Icon class="w-6 h-6" :class="isUrl('anos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="circle-stack" />
            <div class="ml-2" :class="isUrl('anos') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Años</div>
          </Link>
        </div>
        <!-- End Years Catalog -->

        <!-- Documents Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('documentos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/documentos">
            <Icon class="w-6 h-6" :class="isUrl('documentos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="documents" />
            <div class="ml-2" :class="isUrl('documentos') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Documentos</div>
          </Link>
        </div>
        <!-- End Documents Catalog -->
      </div>
    </div>
    <!-- End General Catalogs Links -->

    <!-- Wells Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="well-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-5 h-5 fill-zinc-300" name="office" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Pozos</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="well-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Well Details Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('pozos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/pozos">
            <Icon class="w-5 h-5" :class="isUrl('pozos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="printer" />
            <div class="ml-2" :class="isUrl('pozos') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Reportes</div>
          </Link>
        </div>
        <!-- End Well Details Catalog -->

        <!-- Well Documents Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('usuarios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/usuarios">
            <Icon class="w-6 h-6" :class="isUrl('usuarios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="documents" />
            <div class="ml-2" :class="isUrl('usuarios') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Documentos</div>
          </Link>
        </div>
        <!-- End Well Documents Catalog -->

        <!-- End Well Components Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('usuarios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/usuarios">
            <Icon class="w-6 h-6" :class="isUrl('usuarios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="components" />
            <div class="ml-2" :class="isUrl('usuarios') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Componentes</div>
          </Link>
        </div>
        <!-- End Well Components Catalog -->
      </div>
    </div>
    <!-- End Wells Catalogs Links -->

    <!-- Chromatography Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="chromatography-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-5 h-5 fill-zinc-300" name="calculator" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Cromatografías</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="chromatography-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Well Details Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('pozos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/pozos">
            <Icon class="w-5 h-5" :class="isUrl('pozos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="fire" />
            <div class="ml-2" :class="isUrl('pozos') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Gas</div>
          </Link>
        </div>
        <!-- End Well Details Catalog -->

        <!-- Well Documents Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('usuarios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/usuarios">
            <Icon class="w-6 h-6" :class="isUrl('usuarios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="liquid" />
            <div class="ml-2" :class="isUrl('usuarios') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Liquída</div>
          </Link>
        </div>
        <!-- End Well Documents Catalog -->
      </div>
    </div>
    <!-- End Chromatography Catalogs Links -->

    <!-- Charts Link -->
    <div class="mb-4">
      <Link class="group flex text-base items-center p-2 rounded-md" :class="isUrl('graficas') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/graficas">
        <Icon class="w-5 h-5" :class="isUrl('graficas') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="chart" />
        <div class="ml-2" :class="isUrl('graficas') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Gráficas</div>
      </Link>
    </div>
    <!-- End Charts Link -->
  </div>
</template>
