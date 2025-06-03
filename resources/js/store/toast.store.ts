import { Toast } from '@/definitions/interfaces';
import { defineStore } from 'pinia';
import { reactive } from 'vue';

export const useToastStore = defineStore('toast', () => {
    const toasts = reactive([] as Toast[]);

    function pushToast(payload: Omit<Toast, 'id'>) {
        const id = Date.now();
        this.toasts.push({ id, ...payload });

        setTimeout(() => {
            this.removeToast(id);
        }, payload.duration);
    }

    function removeToast(id: string) {
        this.toasts = this.toasts.filter((t) => t.id !== id);
    }

    return {
        toasts,
        pushToast,
        removeToast,
    };
});
