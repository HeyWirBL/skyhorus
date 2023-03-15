<script setup>
import { Head } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'
</script>

<template>
  <div>
    <Head title="Información Técnica" />

    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-semibold leading-6 text-gray-900">Información del Sistema</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Detalles del software.</p>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Software</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">v2.5.2</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Empresa</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">PetroHorus International S.A. de C.V.</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Sobre el proyecto</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Plataforma tecnológica para el almacenamiento de datos operativos y documental de los servicios de medición de hidrocarburos.</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">Desarrolladores</dt>
            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
              <ul role="list" class="divide-y divide-gray-200 rounded-md border border-gray-200">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 fill-gray-400" name="at-symbol" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">jesus.mendez@labsolutionsit.com.mx</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="mailto:jesus.mendez@labsolutionsit.com.mx" class="font-medium text-yellow-600 hover:text-yellow-500">Contactar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 fill-gray-400" name="at-symbol" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">sam.hernandezc@labsolutionsit.com.mx</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="mailto:sam.hernandezc@labsolutionsit.com.mx" class="font-medium text-yellow-600 hover:text-yellow-500">Contactar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 fill-gray-400" name="at-symbol" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">miguel.martinez@labsolutionsit.com.mx</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="mailto:miguel.martinez@labsolutionsit.com.mx" class="font-medium text-yellow-600 hover:text-yellow-500">Contactar</a>
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
