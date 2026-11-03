import '../css/app.css';
import './bootstrap'; // Configuración de Axios y route
import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { route } from './route';

createInertiaApp({
    title: (title) => {
        if (!title) return 'IESTP Francisco Vigo Caballero';
        return title.includes('Vigo Caballero') ? title : `${title} — IESTP Francisco Vigo Caballero`;
    },
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.route = route;
        app.provide('route', route);
        app.component('Link', Link);
        app.component('Head', Head);
        app.use(plugin).mount(el);
    },
    progress: {
        color: '#2563eb',
        includeCSS: true,
        showSpinner: false,
    },
});