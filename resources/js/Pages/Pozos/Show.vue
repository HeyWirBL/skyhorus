<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  pozo: Object,
})
</script>

<template>
  <div>
    <Head :title="`${pozo.nombre_pozo}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/pozos">Pozos</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ pozo.nombre_pozo }}
      </h1>
      <Link class="btn-yellow ml-auto" :href="`/pozos/${pozo.id}/editar`">
        <span>Editar</span>
        <span class="hidden md:inline">&nbsp;Pozo</span>
      </Link>
    </div>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Datos del muestreo</h3>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Pozo/Instalación</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.nombre_pozo }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Punto de muestreo</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.punto_muestreo }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Fecha del muestreo</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.fecha_hora }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Presión</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.presion_kgcm2 }} KM/CM2 {{ pozo.presion_psi }} PSIA</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Temperatura</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.temp_C }} °C {{ pozo.temp_F }} °F</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Volúmen</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ pozo.volumen_cm3 }} CM3 {{ pozo.volumen_lts }} LTS</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Identificador</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">
              {{ pozo.identificador }}
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Observaciones</dt>
            <dd v-if="pozo.observaciones" class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">
              {{ pozo.observaciones }}
            </dd>
            <dd v-else class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">Aún no hay observaciones.</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Reportes</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="pencil" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Análisis</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Generar</Link>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Componentes</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Generar</Link>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía de Gas</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Generar</Link>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Cromatografía Líquida</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Generar</Link>
                  </div>
                </li>
              </ul>
            </dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>
