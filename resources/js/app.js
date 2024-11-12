import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Multiselect from "vue-multiselect";
import { Bootstrap4Pagination } from "laravel-vue-pagination";


//HELPERS
import api from "./Helps/api.js";
import { formatDate } from "./Helps/format_date.js";
import alert from "@/Helps/alert.js";
import setFormData from "@/Helps/setFormData.js";


const appName = import.meta.env.VITE_APP_NAME || 'Contreras Corp';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Agregar la instancias a la aplicación global para que esté disponible en todas partes
        app.config.globalProperties.api = api;
        app.config.globalProperties.formatDate = formatDate;
        app.config.globalProperties.alert = alert;
        app.config.globalProperties.setFormData = setFormData;

        // Añadir la función can para verificar permisos
        app.config.globalProperties.can = (permission) => {
            // Accede a los permisos desde props (Inertia)
            const userPermissions = props.initialPage.props.user.permissions || [];
            return userPermissions.includes(permission);
        };
        
        app.config.globalProperties.company = props.initialPage.props.company;
        app.config.globalProperties.user = props.initialPage.props.user;


        app.component("Multiselect", Multiselect);
        app.component("Pagination", Bootstrap4Pagination);


        return app.use(plugin).use(ZiggyVue).mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
