<script setup>
import { Link } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import Icon from '@/Components/Icon.vue'
import MainMenu from '../Shared/MainMenu.vue'
import FlashMessages from '../Shared/FlashMessages.vue'

/* defineProps({auth: Object}); */

const showMenu = () => {
  document.getElementById('sidebar').style.display="";
}
</script>

<template>
  <div>
    <div id="dropdown" />
    <div class="md:flex md:flex-col md:h-screen">
      <div class="md:flex md:flex-shrink-0">
        <div class="flex items-center justify-between px-6 py-4 bg-zinc-900 md:flex-shrink-0 md:justify-center md:w-56">
          <Link class="text-white" href="/"> GestionDocumental </Link>
          <Dropdown :auto-close="false" class="md:hidden" placement="bottom-end">
            <template #default>
              <Icon class="w-6 h-6 fill-gray-200 group-hover:fill-white focus:fill-white" name="bars-3" />
            </template>
            <template #dropdown>
              <div class="mt-2 px-8 py-4 bg-zinc-800 rounded shadow-lg">
                <MainMenu />
              </div>
            </template>
          </Dropdown>
        </div>
        <div class="md:text-md flex items-center justify-between p-4 w-full text-sm bg-yellow-500 border-b md:px-12 md:py-0">
          <div class="flex">
            <Icon name="bars-3" class="fill-white w-3 h-3 mt-1 mr-1"/>
            <button class="text-white" @click="showMenu">Mostrar menú</button>
          </div>
          <div class="text-white mr-4">Sky Horus</div>
          <Dropdown placement="bottom-end">
            <template #default>
              <div class="group flex items-center cursor-pointer select-none">
                <div class="mr-1 text-gray-200 group-hover:text-white focus:text-white whitespace-nowrap">
                  <span>Bienvenido(a) {{ $page.props.auth.user.nombre }}</span>
                  <span class="hidden md:inline">&nbsp;{{ $page.props.auth.user.apellidos }}</span>
                </div>
                <Icon class="w-5 h-5 fill-gray-200 group-hover:fill-white focus:fill-white" name="cheveron-down" />
              </div>
            </template>
            <template #dropdown>
              <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                <Link class="block px-6 py-2 hover:text-white hover:bg-yellow-500" href="/perfil">Perfil del Usuario</Link>
                <Link class="block px-6 py-2 hover:text-white hover:bg-yellow-500" href="/menu">Ver Menú</Link>
                <Link class="block px-6 py-2 hover:text-white hover:bg-yellow-500" href="/informacion">Información del Sistema</Link>
                <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-yellow-500" href="/logout" method="post" as="button">Desconectarse</Link>
              </div>
            </template>
          </Dropdown>
        </div>
      </div>
      <div class="md:flex md:flex-grow md:overflow-hidden">
        <MainMenu class="hidden flex-shrink-0 py-4 px-3 w-56 bg-zinc-800 overflow-y-auto md:block" />
        <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
          <FlashMessages />
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
