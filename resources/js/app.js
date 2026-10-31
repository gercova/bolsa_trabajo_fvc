import '../css/app.css';
import './bootstrap'; // Configuración de Axios y route
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { route } from './route';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.route = route;
        app.provide('route', route);
        app.use(plugin).mount(el);
    },
});