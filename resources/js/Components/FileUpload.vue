<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>
    <div class="form-input p-0" :class="{ error: errors.length }">
      <input ref="file" type="file" :accept="accept" class="hidden" />
      <div v-if="!modelValue" class="p-2">
        <button class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm" type="button" @click="browse">Elegir...</button>
      </div>
      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1">{{ filesize(modelValue.size) }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  modelValue: {
    type: File,
  },
  accept: String,
  label: String,
  errors: {
    type: Array,
    default: () => [],
  },
})

const file = ref(null)

const browse = () => {
  file.value.click()
}

const filesize = () => {
  const file = Math.floor(1 / 2)
  console.log(file)
}
</script>
