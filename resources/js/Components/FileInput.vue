<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: [File, String, Array],
    default: () => [],
  },
  label: String,
  accept: String,
  errors: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['update:modelValue'])

const fileInput = ref(null)

const browseFiles = () => {
  fileInput.value.click()
}

const addFiles = (e) => {
  emit('update:modelValue', e.target.files[0])
}

const removeFile = () => {
  emit('update:modelValue', null)
}

watch(
  () => props.modelValue,
  (value) => {
    if (!value) {
      fileInput.value = ''
    }
  },
)
</script>

<template>
  <div>
    <label v-if="label" class="form-label">{{ label }}:</label>
    <div class="form-input p-0" :class="{ error: errors.length }">
      <input ref="fileInput" type="file" :accept="accept" class="hidden" @change="addFiles" />
      <div v-if="!modelValue" class="p-2">
        <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm" @click="browseFiles">Elegir...</button>
      </div>
      <div v-else class="flex items-center justify-between p-2">
        <div class="flex-1 pr-1">
          {{ modelValue.name }} <span class="text-gray-500 text-xs">({{ $filesize(modelValue.size) }})</span>
        </div>
        <button type="button" class="px-4 py-1 text-white text-xs font-medium bg-gray-500 hover:bg-gray-700 rounded-sm" @click.prevent="removeFile">Remover</button>
      </div>
    </div>
    <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
  </div>
</template>
