<script setup>
import { onMounted, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'

const props = defineProps({
  id: {
    type: String,
    default() {
      return `textarea-input-${uuidv4()}`
    },
  },
  error: String,
  label: String,
  modelValue: String,
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
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ props.label }}</label>
    <textarea :id="id" ref="textarea" v-bind="{ ...$attrs, class: null }" class="form-textarea" :class="{ error: error }" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
