<script setup>
import { computed, ref, watch } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import Icon from '@/Components/Icon.vue';
import Dropside from '@/Components/Dropside.vue';
const currentUrl = computed(() => usePage().url.substring(1))

const props = defineProps({
    datefilter: Object,
});

const df = ref({
  month: props.datefilter,
  year: props.datefilter,
});

const isUrl = (...urls) => {
  if (urls[0] === '') {
    return currentUrl.value === ''
  }
  return urls.filter((url) => currentUrl.value.startsWith(url)).length
}

watch( df.value, () => {
  router.get('/componente-pozos',df.value,{ preserveState: true, replace: true, preserveScroll: true })
}
)

</script>

<script>
export default {
    data() {
        return {
            show: true,
        };
    },
    watch: {
        "$page.props.auth.can": {
            handler() {
                this.show = true;
            },
            deep: true,
        },
    },
    components: { Dropside }
}
</script>

<template>
  <div>
    <!-- Dashboard Link -->
    <div class="mb-4">
      <Link class="group flex text-base items-center p-2 rounded-md" :class="isUrl('') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/">
        <Icon class="w-5 h-5" :class="isUrl('') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="dashboard" />
        <div class="ml-2" :class="isUrl('') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <!-- End Dashboard Link -->

    <!-- General Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="general-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-6 h-6" name="rectangle-group" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Generales</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="general-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Users Catalog -->
        <div v-if="$page.props.auth.can.viewAnyUser && show" class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('users') || isUrl('perfil') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/users">
            <Icon class="w-5 h-5" :class="isUrl('users') || isUrl('perfil') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="users" />
            <div class="ml-2" :class="isUrl('users') || isUrl('perfil') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Usuarios</div>
          </Link>
        </div>
        <!-- End Users Catalog -->

        <!-- Folders Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('directorios') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/directorios">
            <Icon class="w-6 h-6" :class="isUrl('directorios') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="folders" />
            <div class="ml-2" :class="isUrl('directorios') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Carpetas</div>
          </Link>
        </div>
        <!-- End Folders Catalog -->

        <!-- Years Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('anos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/anos">
            <Icon class="w-6 h-6" :class="isUrl('anos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="calendar" />
            <div class="ml-2" :class="isUrl('anos') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Años</div>
          </Link>
        </div>
        <!-- End Years Catalog -->

        <!-- Documents Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('documentos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/documentos">
            <Icon class="w-6 h-6" :class="isUrl('documentos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="documents" />
            <div class="ml-2" :class="isUrl('documentos') ? 'text-white group-hover:text-white' : 'text-zinc-300 group-hover:text-white'">Documentos</div>
          </Link>
        </div>
        <!-- End Documents Catalog -->
      </div>
    </div>
    <!-- End General Catalogs Links -->

    <!-- Wells Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="well-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-5 h-5 fill-zinc-300" name="office" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Pozos</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="well-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Well Details Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('pozos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/pozos">
            <Icon class="w-5 h-5" :class="isUrl('pozos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="printer" />
            <div class="ml-2" :class="isUrl('pozos') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Pozos</div>
          </Link>
        </div>
        <!-- End Well Details Catalog -->

        <!-- Well Documents Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('doc-pozos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/doc-pozos">
            <Icon class="w-6 h-6" :class="isUrl('doc-pozos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="documents" />
            <div class="ml-2" :class="isUrl('doc-pozos') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Documentos</div>
          </Link>
        </div>
        <!-- End Well Documents Catalog -->

        <!-- End Well Components Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('componente-pozos') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/componente-pozos">
            <Icon class="w-6 h-6" :class="isUrl('componente-pozos') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="components" />
            <div class="ml-2" :class="isUrl('componente-pozos') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Componentes</div>
          </Link>
        </div>
        <Dropside :auto-close="false">
          <template #default>
            <div class="group flex text-base items-center p-2 pl-11 mt-1 rounded-md text-white justify-between hover:cursor-pointer hover:bg-zinc-700" :class="isUrl('componente-pozos') ? '' : 'hidden'">
              Filtrar
              <Icon class="w-5 h-5 ml-20 fill-white align-middle" name="cheveron-right"/>
            </div>
          </template>
          <template #dropdown>
            <div class="flex px-4 py-4 bg-zinc-800 rounded shadow-lg">
              <div class="flex flex-col mx-2">
                <label class="text-zinc-300 mb-2">Año:</label>
                <select class="rounded-lg bg-zinc-500 text-zinc-300" v-model="df.year">
                  <option :value="null"></option>
                  <option value="2020">2020</option>
                  <option value="2021">2021</option>
                  <option value="2020">2022</option>
                  <option value="2023">2023</option>
                </select>
              </div>
              <div class="flex flex-col mx-2">
                <label class="text-zinc-300 mb-2">Mes:</label>
                <select class="rounded-lg bg-zinc-500 text-zinc-300" v-model="df.month">
                  <option :value="null"></option>
                  <option value="1">Enero</option>
                  <option value="2">Febrero</option>
                  <option value="3">Marzo</option>
                  <option value="4">Abril</option>
                  <option value="5">Mayo</option>
                  <option value="6">Junio</option>
                  <option value="7">Julio</option>
                  <option value="8">Agosto</option>
                  <option value="9">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
            </div>
          </template>
        </Dropside>
        <!-- End Well Components Catalog -->
      </div>
    </div>
    <!-- End Wells Catalogs Links -->

    <!-- Chromatography Catalogs Links -->
    <div class="mb-4">
      <button class="mb-2 w-full">
        <label for="chromatography-catalogs">
          <div class="group flex text-base text-zinc-300 items-center p-2 rounded-md cursor-pointer hover:bg-zinc-700">
            <Icon class="w-5 h-5 fill-zinc-300" name="calculator" />
            <span class="flex-1 ml-2 text-left whitespace-nowrap">Cromatografías</span>
            <Icon class="w-5 h-5 fill-zinc-300" name="cheveron-down" />
          </div>
        </label>
      </button>
      <input id="chromatography-catalogs" class="peer hidden" type="checkbox" checked />
      <div class="peer-checked:hidden">
        <!-- Gas Chromatography Catalog -->
        <div class="mb-2">
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('cromatografia-gases') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/cromatografia-gases">
            <Icon class="w-5 h-5" :class="isUrl('cromatografia-gases') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="fire" />
            <div class="ml-2" :class="isUrl('cromatografia-gases') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Gas</div>
          </Link>
        </div>
        <!-- Gas Chromatography Catalog -->

        <!-- Liquid Chromatography Catalog -->
        <div>
          <Link class="group flex text-base items-center p-2 pl-11 rounded-md" :class="isUrl('cromatografia-liquidas') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/cromatografia-liquidas">
            <Icon class="w-6 h-6" :class="isUrl('cromatografia-liquidas') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="liquid" />
            <div class="ml-2" :class="isUrl('cromatografia-liquidas') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Liquida</div>
          </Link>
        </div>
        <!-- Liquid Chromatography Catalog -->
      </div>
    </div>
    <!-- End Chromatography Catalogs Links -->

    <!-- Charts Link -->
    <div class="mb-4">
      <Link class="group flex text-base items-center p-2 rounded-md" :class="isUrl('graficas') ? 'bg-yellow-500' : 'hover:bg-zinc-700'" href="/graficas">
        <Icon class="w-5 h-5" :class="isUrl('graficas') ? 'fill-white' : 'fill-zinc-300 group-hover:fill-white'" name="chart" />
        <div class="ml-2" :class="isUrl('graficas') ? 'text-white' : 'text-zinc-300 group-hover:text-white'">Gráficas</div>
      </Link>
    </div>
    <!-- End Charts Link -->
  </div>
</template>
