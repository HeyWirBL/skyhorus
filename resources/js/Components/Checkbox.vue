<script>
export default {
  value: null,
}
</script>

<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
  checked: {
    type: [Array, Boolean],
    default: false,
  },
})

const proxyChecked = computed({
  get() {
    return props.checked
  },

  set(val) {
    emit('update:checked', val)
  },
})
</script>

<template>
  <input v-model="proxyChecked" type="checkbox" :value="value" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" />
</template>
