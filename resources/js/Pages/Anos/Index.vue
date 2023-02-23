<script setup>
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Icon from '@/Components/Icon.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
  filters: Object,
  anos: Object,
})

const form = ref({
  search: props.filters.search,
  trashed: props.filters.trashed,
})

watch(
  () => form.value,
  debounce(function () {
    router.get('/anos', pickBy(form.value), { preserveState: true, replace: true })
  }, 300),
  {
    deep: true,
  },
)

const reset = () => {
  form.value = mapValues(form.value, () => null)
}
</script>

<template>
  <div>
    <Head title="Años" />
    <h1 class="mb-8 text-3xl font-bold">Años</h1>
    <div class="flex items-center justify-between mb-6">
      <SearchFilter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mt-4 text-gray-700">Eliminado:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">Con Modificación</option>
          <option value="only">Solo Eliminado</option>
        </select>
      </SearchFilter>
      <Link class="btn-yellow" href="/anos/crear">
        <span>Crear</span>
        <span class="hidden md:inline">&nbsp;Año</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="px-6 py-3" colspan="2">Año</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ano in props.anos.data" :key="ano.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td>
              <Link class="flex items-center px-6 py-4 focus:text-yellow-500" :href="`/anos/${ano.id}/editar`" tabindex="-1">
                {{ ano.ano }}
                <Icon v-if="ano.deleted_at" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" name="trash" />
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/anos/${ano.id}/editar`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.anos.data.length === 0">
            <td class="px-6 py-4" colspan="3">No se encontraron años registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Paginator -->
    <Pagination class="mt-4" :links="props.anos.links" :total="props.anos.total" />
  </div>
</template>
