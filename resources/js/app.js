import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
// import NavLayout from './Layouts/NavLayout.vue';
import './progress.css';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`];
    
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
  progress: {
    delay: 200,
    color: '#4f46e5', // Indigo color for better visibility
    includeCSS: false, // We'll use our custom CSS
    showSpinner: true,
},
})