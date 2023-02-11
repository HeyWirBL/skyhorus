import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import Layout from '@/Shared/Layout.vue'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Petro Horus'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))

    await page.then((module) => {
      module.default.layout = name.startsWith('Auth/') ? undefined : module.default.layout || Layout
    })
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
  progress: {
    color: '#cc9900',
  },
})
