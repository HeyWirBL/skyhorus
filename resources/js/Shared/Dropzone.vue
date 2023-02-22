<script setup>
import { ref } from 'vue'

const isDragging = ref(false)
const $emit = defineEmits(['drop'])

const handleDragOver = (e) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = (e) => {
  e.preventDefault()
  isDragging.value = false
}

const handleDrop = (e) => {
  e.preventDefault()
  isDragging.value = false
  const files = Array.from(e.dataTransfer.files)
  $emit('drop', files)
}
</script>

<template>
  <div class="dropzone" :class="{ dragging: isDragging }" @dragover="handleDragOver" @dragleave="handleDragLeave" @drop="handleDrop">
    <p v-if="!isDragging">Drag and drop files here</p>
    <p v-else>Release to drop files</p>
  </div>
</template>
