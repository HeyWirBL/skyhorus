<script setup>
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { createPopper } from '@popperjs/core'

const open = ref(false)
const button = ref(null)
const dropdown = ref(null)

const props = defineProps({
  placement: {
    type: String,
    default: 'bottom-end',
  },
  autoClose: {
    type: Boolean,
    default: true,
  },
})

watch(
  () => open.value,
  (open) => {
    if (open) {
      nextTick(() => {
        return createPopper(button.value, dropdown.value, {
          placement: props.placement,
          modifiers: [
            {
              name: 'preventOverflow',
              options: {
                altBoundary: true,
              },
            },
          ],
        })
      })
    }
  },
)

const closeOnEscape = (e) => {
  if (open.value && e.key === 'Escape') {
    open.value = false
  }
}

onMounted(() => document.addEventListener('keydown', closeOnEscape))
onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape)
})
</script>

<template>
  <button ref="button" type="button" @click="open = !open">
    <slot />
    <!-- Full Screen Dropdown Overlay -->
    <teleport v-if="open" to="#dropdown">
      <div>
        <div style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: 0.2" @click="open = false" />
        <div ref="dropdown" style="position: absolute; z-index: 99999" @click.stop="open = !autoClose">
          <slot name="dropdown" />
        </div>
      </div>
    </teleport>
  </button>
</template>
