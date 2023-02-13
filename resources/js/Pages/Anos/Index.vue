<script setup>
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  anos: Array,
})

const selected = ref([])
const selectAll = ref(false)

const select = () => {
  selected.value = []
  if (!selectAll.value) {
    for (let i in props.anos) {
      selected.value.push(props.anos[i].idAno)
    }
  }
}
</script>

<template>
  <div>
    <Head title="Años" />
    <h1 class="mb-8 text-3xl font-bold">Años</h1>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead class="text-sm text-left font-bold uppercase bg-white border-b">
          <tr>
            <th scope="col" class="p-4">
              <div class="flex items-center">
                <input id="checkbox-all-users" v-model="selectAll" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="select" />
                <label for="checkbox-all-users" class="sr-only">checkbox</label>
              </div>
            </th>
            <th scope="col" colspan="2" class="px-6 py-3">Año</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ano in props.anos" :key="ano.idAno" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
            <td class="w-4 p-4">
              <div class="flex items-center">
                <input :id="`checkbox-user-${ano.idAno}`" v-model="selected" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="ano.idAno" />
                <label :for="`checkbox-user-${ano.idAno}`" class="sr-only">checkbox</label>
              </div>
            </td>
            <td>
              <Link class="flex items-center px-6 py-4" :href="`/anos/${ano.idAno}/edit`" tabindex="-1">
                {{ ano.ano }}
              </Link>
            </td>
            <td class="w-px">
              <Link class="flex items-center px-6" :href="`/anos/${ano.idAno}/edit`" tabindex="-1">
                <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
              </Link>
            </td>
          </tr>
          <tr v-if="props.anos.length === 0">
            <td class="px-6 py-4">No se encontraron años registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
