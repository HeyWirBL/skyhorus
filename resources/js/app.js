import '../css/app.css'
import { filesize } from './utils'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import MainLayout from '@/Layouts/MainLayout.vue'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Petro Horus'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    const pages = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))

    await pages.then((page) => {
      page.default.layout = name.startsWith('Auth/') ? undefined : page.default.layout || MainLayout
    })

    return pages
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.config.globalProperties.$filesize = filesize

    app.use(plugin).use(VueSweetalert2).mount(el)
  },
})
