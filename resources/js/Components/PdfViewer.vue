<script setup>
import { ref } from 'vue'
import Modal from './Modal.vue';
import VuePdfApp from 'vue3-pdf-app'
import "vue3-pdf-app/dist/icons/main.css";

const originPath    = window.location.origin
defineProps({
    docName: String,
    open: Boolean,
})
console.log(originPath)
</script>

<template>
    <Modal :show="open || false">
        <vue-pdf-app style="height: 85vh;" :pdf="originPath+'/storage/files/'+docName" :theme="'light'">
        </vue-pdf-app>
  
        <div class="flex items-center justify-end p-4 space-x-2 border-t border-gray-200">
          <button class="btn-yellow" @click="open = false">Cerrar</button>
        </div>
      </Modal>
</template>