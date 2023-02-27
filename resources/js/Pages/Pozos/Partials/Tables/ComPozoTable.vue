<script setup>
import { computed } from 'vue'

const props = defineProps({
  pozo: Object,
})

const componentePozos = computed(() => props.pozo.componentePozos)
</script>

<template>
  <div>
    <table class="w-full whitespace-nowrap text-sm">
      <thead class="bg-white border-b-2">
        <tr>
          <th v-if="componentePozos.data.length !== 0" scope="col" class="border px-4 py-4" style="width: 30px">
            <input id="checkbox-all-compozos" v-model="selectAllComPozos" type="checkbox" class="text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllComPozos" />
            <label for="checkbox-all-compozos" class="sr-only">checkbox</label>
          </th>
          <th scope="col" class="border px-6 py-4" />
          <th scope="col" class="border px-6 py-4" />
          <th scope="col" class="border px-6 py-4" />
          <th scope="col" class="border px-6 py-4" />
          <th scope="col" class="border px-6 py-4" />
          <th class="border px-6 py-4" />
        </tr>
      </thead>
      <tbody>
        <template v-for="(compozo, rowIndex) in componentePozos.data" :key="compozo.id">
          <tr v-if="rowIndex !== 0" class="bg-gray-100">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">{{ pozo.nombre_pozo }}</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="3">
              <span class="block bg-gray-400 text-white font-normal leading-6">Datos de análisis en laboratorio</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="2">
              <span class="block bg-gray-400 text-white font-normal leading-6">Nombre del grupo</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4">
              <div class="flex items-center">
                <input :id="`checkbox-compozos-${compozo.id}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="compozo.id" @change="changeToggleAllComPozos" />
                <label :for="`checkbox-compozos-${compozo.id}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de recepción</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de análisis</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Número de determinación</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ compozo.nombre_componente }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="text-center border px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.fecha_recep }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.fecha_analisis }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.no_determinacion }}</span>
            </td>
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
          </tr>
          <tr class="bg-white">
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Equipo utilizado</span>
            </td>
            <td class="text-center border px-6 py-4" colspan="2">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Método de laboratorio</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Fecha de muestreo</span>
            </td>
            <td class="text-center border px-6 py-4">
              <span class="block leading-6">{{ compozo.fecha_muestreo }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.equipo_utilizado }}</span>
            </td>
            <td class="border text-center px-6 py-4" colspan="2">
              <span class="block leading-6">
                {{ compozo.met_laboratorio.substring(0, 40) }}
                <a class="text-yellow-400 hover:underline" href="#" @click="openModalMessageMet(index)">Ver Más...</a>
              </span>
            </td>
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4">
              <span class="block bg-yellow-400 text-white underline cursor-pointer leading-6">Observaciones</span>
            </td>
            <td class="text-left border px-6 py-4" colspan="2">
              <span class="block leading-6">{{ compozo.observaciones.substring(0, 38) }} <a class="text-yellow-400 hover:underline" href="#" @click="openModalObs(index)">Ver Más...</a></span>
            </td>
            <td class="text-center border px-6 py-4" />
            <td class="text-center border px-6 py-4" />
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Información</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">PM</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">% Peso</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">% MOL</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Densidad</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Dióxido de carbono</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.dioxido_carbono }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_dioxido_carbono }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Ácido sulfhídrico</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.acido_sulfidrico }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_acido_sulfidrico }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Nitrógeno</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.nitrogeno }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_nitrogeno }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Metano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.metano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_metano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Etano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.etano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_etano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Propano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.propano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_propano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Iso-Butano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.iso_butano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_iso_butano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">n-Butano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.n_butano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_n_butano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">Iso-Pentano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.iso_pentano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_iso_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">n-Petano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.n_pentano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_n_pentano }}</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">**n-Exano</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.n_exano }}</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">43</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">54</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block leading-6">{{ compozo.den_n_exano }}</span>
            </td>
          </tr>
          <tr class="bg-gray-50">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4">
              <span class="block bg-gray-400 text-white font-normal leading-6">Total</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">540.86</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">100.00</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">100</span>
            </td>
            <td class="border text-center px-6 py-4">
              <span class="block bg-yellow-400 text-white font-bold leading-6">0</span>
            </td>
          </tr>
          <tr class="bg-white">
            <td class="border text-center px-6 py-4" />
            <td class="border text-center px-6 py-4" />
            <td class="border text-left px-6 py-4" colspan="5">
              <ul role="list" class="divide-y divide-gray-400 rounded-md border border-gray-400">
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="paper-clip" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Documento PDF</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" type="button">Descargar</a>
                  </div>
                </li>
                <li class="flex items-center justify-between py-3 pl-3 pr-4 text-base">
                  <div class="flex w-0 flex-1 items-center">
                    <Icon class="h-5 w-5 flex-shrink-0 text-gray-500" name="line-chart" aria-hidden="true" />
                    <span class="ml-2 w-0 flex-1 truncate">Gráfica de líneas % MOL</span>
                  </div>
                  <div class="ml-4 flex-shrink-0">
                    <a href="#" class="font-medium text-yellow-600 hover:text-yellow-500" @click="openModalChart(index)">Visualizar</a>
                  </div>
                </li>
              </ul>
            </td>
          </tr>
        </template>
        <tr v-if="componentePozos.data.length === 0">
          <td class="px-6 py-4" colspan="4">No se encontraron componentes de pozo registrados.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
