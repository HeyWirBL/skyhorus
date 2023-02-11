<script setup>
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { createPopper } from '@popperjs/core'

const show = ref(false)
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
  () => show.value,
  (show) => {
    if (show) {
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

const close = () => {
  if (props.autoClose) {
    show.value = false
  }
}

const closeOnEscape = (e) => {
  if (e.key === 'Escape' && show.value) {
    close()
  }
}

onMounted(() => document.addEventListener('keydown', closeOnEscape))

onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape)
  document.body.style.overflow = null
})
</script>

<template>
  <button ref="button" type="button" @click="show = true">
    <slot />
    <teleport v-if="show" to="#dropdown">
      <div>
        <div style="position: fixed; top: 0; right: 0; left: 0; bottom: 0; z-index: 99998; background: black; opacity: 0.2" @click="show = false" />
        <div ref="dropdown" style="position: absolute; z-index: 99999" @click.stop="show = !autoClose">
          <slot name="dropdown" />
        </div>
      </div>
    </teleport>
  </button>
</template>
