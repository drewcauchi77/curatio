import { FlashResponse } from '@/definitions/interfaces';
import { useToastStore } from '@/store/toast.store';
import { getCurrentInstance, onMounted } from 'vue';

export function useFlashMessages(flash: FlashResponse, duration: number) {
    const toastStore = useToastStore();
    const instance = getCurrentInstance();
    const proxy = instance?.proxy;

    const handleFlashMessage = (): void => {
        if (flash?.message !== null && flash?.title !== null) {
            toastStore.pushToast({
                type: flash?.type,
                title: proxy.$t(flash.title),
                message: proxy.$t(flash.message),
                duration: duration,
            });
        }
    };

    onMounted((): void => {
        handleFlashMessage();
    });

    return { handleFlashMessage };
}
