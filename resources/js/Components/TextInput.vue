<script setup>
import { onMounted, ref } from 'vue'
import { v4 as uuidv4 } from 'uuid'

const props = defineProps({
  id: {
    type: String,
    default() {
      return `text-input-${uuidv4()}`
    },
  },
  type: {
    type: String,
    default: 'text',
  },
  error: String,
  label: String,
  modelValue: String,
})

defineEmits(['update:modelValue'])

// const inheritAttrs = ref(false)
const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ props.label }}</label>
    <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :type="props.type" :value="props.modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
