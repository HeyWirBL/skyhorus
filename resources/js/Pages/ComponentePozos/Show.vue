<script setup>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

defineProps({
  componentePozo: Object,
})
</script>

<template>
  <div>
    <Head :title="`${componentePozo.nombre_componente}`" />
    <div class="flex items-center justify-start mb-8 w-full">
      <h1 class="text-3xl font-bold">
        <Link class="text-yellow-400 hover:text-yellow-600" href="/componente-pozos">Componentes del Pozo</Link>
        <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ componentePozo.nombre_componente }}
      </h1>
      <Link class="btn-yellow ml-auto" :href="`/componente-pozos/${componentePozo.id}/editar`">
        <span>Editar</span>
        <span class="hidden md:inline">&nbsp;Componentes</span>
      </Link>
    </div>
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Datos de análisis en laboratorio</h3>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Pozo/Instalación</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.pozo.nombre_pozo }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Nombre del grupo</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.nombre_componente }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Equipo utilizado</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.equipo_utilizado }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Método del laboratorio</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.met_laboratorio }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Número de determinación</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.no_determinacion }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Fecha de recepción</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.fecha_recep }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Fecha de análisis</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.fecha_analisis }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Fecha de muestreo</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.fecha_muestreo }}</dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Observaciones</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">{{ componentePozo.observaciones }}</dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Componentes</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0 rounded-md shadow overflow-x-auto">
              <table class="w-full whitespace-nowrap">
                <thead class="text-sm text-left font-bold uppercase bg-white border-b">
                  <tr>
                    <th scope="col" class="px-6 py-3">Información</th>
                    <th scope="col" class="px-6 py-3">PM</th>
                    <th scope="col" class="px-6 py-3">% Peso</th>
                    <th scope="col" class="px-6 py-3">% MOL</th>
                    <th scope="col" class="px-6 py-3">Densidad</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Dióxido de carbono</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.dioxido_carbono }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_dioxido_carbono }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Ácido sulfhídrico</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.acido_sulfidrico }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_acido_sulfidrico }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Nitrógeno</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.nitrogeno }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_nitrogeno }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Metano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.metano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_metano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Etano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.etano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_etano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Propano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.propano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_propano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Isobutano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.iso_butano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_iso_butano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Butano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.n_butano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_n_butano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Isopentano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.iso_pentano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_iso_pentano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Pentano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.n_pentano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_n_pentano }}</span>
                    </td>
                  </tr>
                  <tr class="bg-white border-b">
                    <td>
                      <span class="flex items-center px-6 py-4">Hexano</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.n_exano }}</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">43</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">34</span>
                    </td>
                    <td>
                      <span class="flex items-center px-6 py-4">{{ componentePozo.den_n_exano }}</span>
                    </td>
                  </tr>
                </tbody>
                <tfoot class="text-sm text-left font-bold uppercase bg-white border-b">
                  <tr>
                    <th scope="row" class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">540.86</th>
                    <th class="px-6 py-3">100.00</th>
                    <th class="px-6 py-3">100</th>
                    <th class="px-6 py-3">0</th>
                  </tr>
                </tfoot>
              </table>
            </dd>
          </div>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-base font-medium text-gray-500">Reportes</dt>
            <dd class="mt-1 text-base text-gray-900 sm:col-span-2 sm:mt-0">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Documento PDF</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Descargar</Link>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <Link href="#" class="font-medium text-yellow-600 hover:text-yellow-500">Visualizar</Link>
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
