<script setup>
import Icon from '@/Components/Icon.vue'

defineProps({
  can: Object,
})

defineEmits(['restore'])
</script>

<template>
  <div class="flex items-center justify-between p-4 max-w-3xl rounded" style="background-color: rgb(250, 204, 21)">
    <div class="flex items-center">
      <Icon name="trash" class="flex-shrink-0 mr-2 w-4 h-4 fill-yellow-800" />
      <div class="text-yellow-800 text-sm font-medium">
        <slot />
      </div>
    </div>
    <button class="text-yellow-800 hover:underline text-sm" tabindex="-1" type="button" @click="$emit('restore')">Restablecer</button>
  </div>
</template>
