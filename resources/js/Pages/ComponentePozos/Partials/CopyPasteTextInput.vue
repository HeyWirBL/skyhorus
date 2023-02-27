<script setup>
import { onMounted, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'

defineProps({
  id: {
    type: String,
    default() {
      return `textarea-input-${uuidv4()}`
    },
  },
  modelValue: String,
  label: String,
  accept: String,
})

defineEmits(['update:modelValue'])

const textarea = ref(null)

onMounted(() => {
  if (textarea.value.hasAttribute('autofocus')) {
    textarea.value.focus()
  }
})

defineExpose({ focus: () => textarea.value.focus() })
</script>

<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <div class="form-input p-0">
      <textarea :id="id" ref="textarea" v-bind="{ ...$attrs, class: null }" class="form-textarea" :value="modelValue" style="height: 300px" @input="$emit('update:modelValue', $event.target.value)" />
    </div>
  </div>
</template>
