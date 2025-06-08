import MainLayout from '@/components/layouts/MainLayout.vue';
import MetaTags from '@/components/global/MetaTags.vue';
import type { ImportMetaData } from '@/definitions/types';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia';
import type { DefineComponent } from 'vue';
import { createApp, h, watch } from 'vue';
import i18n from './plugins/i18n';
import { useStatusStore } from './store/status.store';

createInertiaApp({
    title: title => `Curatio | ${title}`,
    progress: {
        color: '#6200ee',
    },
    resolve: async (name: string): Promise<DefineComponent> => {
        const pages = import.meta.glob('./Pages/**/*.vue') as ImportMetaData;
        const page = await pages[`./Pages/${name}.vue`]();

        if (page.default.layout === undefined && page.default.hasLayout !== false) {
            page.default.layout = MainLayout;
        }

        return page.default;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(i18n)
            .component('MetaTags', MetaTags)
            .mount(el);

        const statusStore = useStatusStore();

        watch(
            () => statusStore.isBodyScrollable,
            (scrollable) => {
                document.body.style.overflow = !scrollable ? '' : 'hidden';
            },
            { immediate: true },
        );
    },
});
