<script setup>
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  pozo: Object,
  selected: Array,
})

const emit = defineEmits(['update:selected', 'toggle', 'change'])

const selectAllCromGases = ref(false)

const cromatografiaGases = computed(() => props.pozo.cromatografiaGases)

/**
 * Helper Function that that checks whether the `selectAllRef` flag is set
 * to false.
 *
 * @param {array} items an array of items.
 * @param {array} selectedItems an array of selected items.
 * @param {bool} selectAllRef boolean flag that represents whether all items are selected.
 */
const toggleAll = (items, selectedItems, selectAllRef) => {
  selectedItems.value = []
  if (!selectAllRef.value) {
    selectedItems.value = selectedItems.value.length === items.length ? [] : items.map((item) => item.id)
  }
}

/**
 * Helper Function that updates the state of the "select all" checkbox
 * when individual checkboxes are checked or unchecked.
 *
 * @param {array} items list of items that can be selected.
 * @param {array} selectedItems an array which contains the ids of the items that have been selected.
 * @param {bool} selectAllRef reference that represents the state of the "select all" checkbox.
 */
const changeToggleAll = (items, selectedItems, selectAllRef) => {
  if (items.length === selectedItems.value.length) {
    selectAllRef.value = true
  } else {
    selectAllRef.value = false
  }
}

const toggleAllCromGas = () => {
  toggleAll(cromatografiaGases.value, props.selected, selectAllCromGases)
}

const changeToggleAllCromGas = () => {
  if (cromatografiaGases.value.length === props.selected.length) {
    selectAllCromGases.value = true
  } else {
    selectAllCromGases.value = false
  }

  emit('update:selected', props.selected)
}
</script>

<template>
  <div>
    <table class="w-full whitespace-nowrap">
      <thead class="text-sm text-left font-bold uppercase bg-white border-b">
        <tr>
          <th v-if="cromatografiaGases.length !== 0" scope="col" class="p-4">
            <div class="flex items-center">
              <input id="checkbox-all-cromgases" :checked="selectAllCromGases" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" @click="toggleAllCromGas" />
              <label for="checkbox-all-cromgases" class="sr-only">checkbox</label>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">Archivo</th>
          <th scope="col" class="px-6 py-3" colspan="2">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cromgas in cromatografiaGases" :key="cromgas.id" class="bg-white hover:bg-gray-100 focus-within:bg-gray-100 border-b">
          <td class="w-4 p-4">
            <div class="flex items-center">
              <input :id="`checkbox-cromgas-${cromgas.id}`" :checked="selected.includes(cromgas.id)" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" :value="cromgas.id" @change="changeToggleAllCromGas" />
              <label :for="`checkbox-cromgas-${cromgas.id}`" class="sr-only">checkbox</label>
            </div>
          </td>
          <td>
            <Link class="flex items-center px-6 py-4" :href="`/cromatografia-gases/${cromgas.id}`">
              {{ cromgas.documento }}
            </Link>
          </td>
          <td>
            <Link class="flex items-center px-6 py-4" :href="`/cromatografia-gases/${cromgas.id}`" tabindex="-1">{{ cromgas.fecha_hora }} </Link>
          </td>
          <td class="w-px">
            <Link class="flex items-center px-6" :href="`/cromatografia-gases/${cromgas.id}`" tabindex="-1">
              <Icon class="block w-6 h-6 fill-gray-400" name="cheveron-right" />
            </Link>
          </td>
        </tr>
        <tr v-if="cromatografiaGases.length === 0">
          <td class="px-6 py-4" colspan="4">No se encontraron documentos registrados.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
