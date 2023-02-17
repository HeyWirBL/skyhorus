<script setup>
import { onMounted, ref, watch } from 'vue'
import { v4 as uuidv4 } from 'uuid'

const props = defineProps({
  id: {
    type: String,
    default() {
      return `select-input-${uuidv4()}`
    },
  },
  error: String,
  label: String,
  modelValue: [String, Number, Boolean],
})

const $emit = defineEmits(['update:modelValue'])

const selected = ref(props.modelValue)
const select = ref(null)

watch(
  () => selected.value,
  (selected) => {
    $emit('update:modelValue', selected)
  },
)

onMounted(() => {
  if (select.value.hasAttribute('autofocus')) {
    select.value.focus()
  }
})

defineExpose({ focus: () => select.value.focus() })
</script>

<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ props.label }}</label>
    <select :id="id" ref="select" v-model="selected" v-bind="{ ...$attrs, class: null }" class="form-select">
      <slot />
    </select>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>
