import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Multiselect from "vue-multiselect";
// import 'vue-multiselect/dist/vue-multiselect.css'; // Importa los estilos


//HELPERS
import api from "./Helps/api.js";
import { formatDate } from "./Helps/format_date.js";


const appName = import.meta.env.VITE_APP_NAME || 'ABARROTES';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Agregar la instancias a la aplicación global para que esté disponible en todas partes
        app.config.globalProperties.api = api;
        app.config.globalProperties.formatDate = formatDate;

        app.component("Multiselect", Multiselect);


        return app.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
