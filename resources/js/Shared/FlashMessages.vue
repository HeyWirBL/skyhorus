<script setup>
import Icon from '@/Components/Icon.vue'
</script>

<script>
export default {
  data() {
    return {
      show: true,
    }
  },
  watch: {
    '$page.props.flash': {
      handler() {
        this.show = true
      },
      deep: true,
    },
  },
}
</script>

<template>
  <div>
    <div v-if="$page.props.flash.success && show" class="flex items-center justify-between mb-8 max-w-3xl bg-green-500 rounded">
      <div class="flex items-center">
        <Icon class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white" name="check" />
        <div class="py-4 text-white text-sm font-medium">{{ $page.props.flash.success }}</div>
      </div>
      <button type="button" class="group mr-2 p-2" @click="show = false">
        <Icon class="block w-2 h-2 fill-green-800 group-hover:fill-white" name="close" />
      </button>
    </div>
    <div v-if="($page.props.flash.error || Object.keys($page.props.errors).length > 0) && show" class="flex items-center justify-between mb-8 max-w-3xl bg-red-500 rounded">
      <div class="flex items-center">
        <Icon class="flex-shrink-0 ml-4 mr-2 w-4 h-4 fill-white" name="x-circle" />
        <div v-if="$page.props.flash.error" class="py-4 text-white text-sm font-medium">{{ $page.props.flash.error }}</div>
        <div v-else class="py-4 text-white text-sm font-medium">
          <span v-if="Object.keys($page.props.errors).length === 1">Hay un error en el formulario.</span>
          <span v-else>Hay {{ Object.keys($page.props.errors).length }} errores en el formulario.</span>
        </div>
      </div>
      <button type="button" class="group mr-2 p-2" @click="show = false">
        <Icon class="block w-2 h-2 fill-red-800 group-hover:fill-white" name="close" />
      </button>
    </div>
  </div>
</template>
