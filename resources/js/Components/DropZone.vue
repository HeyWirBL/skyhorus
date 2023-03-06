<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Array,
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
const files = ref(props.modelValue)
const errorMessage = ref('')

/**
 * Converts a file size in bytes to the nearest unit of measurement (B, kB, MB, GB, etc.).
 * @param {number} size - The file size in bytes.
 * @param {number} [precision=2] - The number of decimal places to include in the output. Default is 2.
 * @returns {string} The formatted file size with the appropriate unit of measurement.
 */
const filesize = (size, precision = 2) => {
  const units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB']
  if (size < 0) {
    return 'Tipo de archivo inválido'
  } else if (size === 0) {
    return `0 ${units[0]}`
  } else if (size < 1) {
    return `${(size * 1024).toFixed(precision)} ${units[1]}`
  } else if (size >= Math.pow(1024, units.length - 1)) {
    return `${(size / Math.pow(1024, units.length - 1)).toFixed(precision)} ${units[units.length - 1]}`
  } else {
    const i = Math.floor(Math.log(size) / Math.log(1024))
    const val = (size / Math.pow(1024, i)).toFixed(precision)
    return `${val} ${units[i]}`
  }
}

const browseFiles = () => {
  fileInput.value.click()
}

const addFiles = (e, maxFiles = 10) => {
  try {
    e.preventDefault()

    const selectedFiles = e.dataTransfer ? [...e.dataTransfer.files] : [...e.target.files]

    if (selectedFiles.length === 0) {
      errorMessage.value = 'Por favor use la función de arrastrar y soltar para subir archivos.'
      return
    }

    if (!Array.isArray(files.value)) {
      throw new Error('El valor de los archivos no son de tipo array.')
    }

    const fileAlreadyAdded = checkForDuplicateFiles(selectedFiles, files.value)
    if (fileAlreadyAdded.length > 0) {
      const fileNames = fileAlreadyAdded.map((file) => file.name).join(',')
      errorMessage.value = `Los siguientes archivos ya se habían subido: ${fileNames}`
      return
    }

    if (checkFilesLimit(selectedFiles, files.value, maxFiles)) {
      errorMessage.value = 'Sólo se pueden subir 10 archivos a la vez.'
      return
    }

    if (typeof emit !== 'function') {
      throw new Error('La función Emit no está definida')
    }

    files.value = [...files.value, ...selectedFiles]
    emit('update:modelValue', files.value)
  } catch (err) {
    console.error(err)
    errorMessage.value = 'Ha ocurrido un error al subir los archivos.'
  }
}

const removeFile = (index) => {
  const newFiles = files.value.filter((file, i) => i !== index)
  files.value = newFiles
  if (files.value.length === 0) {
    errorMessage.value = ''
  }
  emit('update:modelValue', newFiles)
}

const checkForDuplicateFiles = (newFiles, existingFiles) => {
  return newFiles.filter((file) => existingFiles.some((f) => f.name === file.name))
}

const checkFilesLimit = (newFiles, existingFiles, limit) => {
  return existingFiles.length + newFiles.length > limit
}

const handleDropFiles = (e, maxFiles = 10) => {
  try {
    e.preventDefault()
    e.stopPropagation()

    const droppedFiles = Array.from(e.dataTransfer ? e.dataTransfer.files : e.target.files)

    if (!Array.isArray(files.value)) {
      throw new Error('El valor de los archivos no son de tipo array.')
    }

    const fileAlreadyAdded = checkForDuplicateFiles(droppedFiles, files.value)
    if (fileAlreadyAdded.length > 0) {
      const fileNames = fileAlreadyAdded.map((file) => file.name).join(',')
      errorMessage.value = `Los siguientes archivos ya se habían subido: ${fileNames}`
      return
    }

    if (checkFilesLimit(droppedFiles, files.value, maxFiles)) {
      errorMessage.value = 'Sólo se pueden subir 10 archivos a la vez.'
      return
    }

    const newFiles = [...files.value, ...droppedFiles]

    if (typeof emit !== 'function') {
      throw new Error('La función Emit no está definida')
    }

    files.value = [...newFiles]
    emit('update:modelValue', files.value)
  } catch (err) {
    console.error(err)
    errorMessage.value = 'Ha ocurrido un error al subir los archivos.'
  }
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
  <div class="relative" @dragenter.prevent @dragover.prevent @drop="handleDropFiles">
    <label v-if="label" class="form-label">{{ label }}</label>
    <div class="h-full w-full overflow-auto flex flex-col" :class="{ error: errors.length }">
      <div class="border-2 border-dashed border-gray-400 py-12 flex flex-col justify-center items-center">
        <p class="mb-3 font-semibold flex flex-wrap justify-center">Arrastre y suelte los archivos aquí o</p>
        <input ref="fileInput" type="file" :accept="accept" class="hidden" multiple @change="addFiles" />
        <button type="button" class="btn-yellow" @click="browseFiles">Seleccione archivos</button>
      </div>
    </div>
    <div v-if="errors.length" class="form-error">{{ errors }}</div>
    <div v-if="errorMessage" class="form-error">{{ errorMessage }}</div>
    <div v-if="files.length !== 0">
      <ul role="list" class="mt-2 divide-y divide-gray-400 rounded border border-gray-400">
        <li v-for="(file, index) in files" :key="index" class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
          <div class="flex w-0 flex-1 items-center">
            <span v-if="file.usrName" class="w-0 flex-1 text-yellow-400 truncate" :title="file.usrName">{{ file.usrName }}</span>
            <span v-else class="w-0 flex-1 text-yellow-400 truncate" :title="file.name">
              {{ file.name }}
            </span>
            <span class="mr-4">{{ filesize(file.size) }}</span>
            <progress :value="file.progress" max="100" />
          </div>
          <div class="ml-4 flex-shrink-0">
            <button class="font-medium text-gray-600 hover:text-gray-500" @click.prevent="removeFile(index)">Remover archivo</button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
