<script setup>
import { inject } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import Icon from '@/Components/Icon.vue'

const props = defineProps({
  can: Object,
  userData: Object,
})

const swal = inject('$swal')

const form = useForm({})

const restore = () => {
  swal({
    title: '¿Estás seguro de querer restablecer a este usuario?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#CEA915',
    cancelButtonColor: '#BDBDBD',
    confirmButtonText: 'Restablecer',
    cancelButtonText: 'Cancelar',
  }).then((result) => {
    if (result.isConfirmed) {
      form.put(`/users/${props.userData.id}/restore`)
    }
  })
}
</script>

<template>
  <div>
    <Head :title="`${userData.nombre} ${userData.apellidos}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-yellow-400 hover:text-yellow-600" href="/users">Usuarios</Link>
      <span class="text-yellow-400 font-medium">&nbsp;/</span> {{ userData.nombre }} {{ userData.apellidos }}
    </h1>
    <TrashedMessage v-if="userData.deleted_at && can.restoreUser" class="mb-6" @restore="restore">Este usuario ha sido eliminado.</TrashedMessage>

    <div class="grid grid-cols-1 xl:grid-cols-3 xl:gap-4">
      <div class="col-span-full xl:col-auto">
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-md shadow 2xl:col-span-2 sm:p-6">
          <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
            <img class="mb-4 rounded-md w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0" :src="'../storage/images/user.jpg'" alt="Usuario de PetroHorus" />
            <div>
              <h3 class="mb-1 text-xl font-bold leading-tight">{{ userData.nombre }} {{ userData.apellidos }}</h3>
              <div class="mb-4 text-sm"><span class="font-bold">Tipo de Rol</span>: {{ userData.rol }}</div>
            </div>
          </div>
        </div>

        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-md shadow 2xl:col-span-2 sm:p-6">
          <div class="flow-root">
            <h3 class="text-xl font-semibold">Información de Contacto</h3>
            <ul class="divide-y divide-gray-200">
              <li class="py-4">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <Icon class="w-5 h-5" name="mail" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <span class="block text-base">{{ userData.email }}</span>
                  </div>
                </div>
              </li>
              <li class="py-4">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <Icon class="w-5 h-5" name="map-pin" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <span class="block text-base">{{ userData.direccion === '' || userData.direccion === null ? 'No tiene una dirección especificada.' : userData.direccion }}</span>
                  </div>
                </div>
              </li>
              <li class="py-4">
                <div class="flex items-center space-x-4">
                  <div class="flex-shrink-0">
                    <Icon class="w-5 h-5" name="phone" />
                  </div>
                  <div class="flex-1 min-w-0">
                    <span class="block text-base">{{ userData.telefono === '' || userData.telefono === null ? 'No tiene un número de teléfono especificado.' : userData.telefono }}</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-span-2">
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow 2xl:col-span-2 sm:p-6">
          <h3 class="mb-4 text-xl font-semibold">Próxima Actualización...</h3>
        </div>
      </div>
    </div>
  </div>
</template>
