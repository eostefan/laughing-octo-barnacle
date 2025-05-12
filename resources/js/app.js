import axios from 'axios';
window.axios = axios;

import { createApp, h } from 'vue'
import { createPinia } from 'pinia'
import { createInertiaApp } from '@inertiajs/vue3'
import Layout from './Pages/Layouts/MainLayout.vue'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy } from './ziggy'
import FontAwesomeIcon from "./utils/Fontawesome";

axios.defaults.withCredentials = true

const pinia = createPinia()

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout

    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .component("FontAwesomeIcon", FontAwesomeIcon)
      .use(plugin)
      .use(pinia)
      .use(ZiggyVue, Ziggy)
      .mount(el)
  },
})