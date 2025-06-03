import { FlashResponse } from '@/definitions/interfaces';
import { useToastStore } from '@/store/toast.store';

export const debounce = <F extends (...args: any[]) => void>(callback: F, wait: number): ((...args: Parameters<F>) => void) => {
    let timeoutId: number;

    return (...args: Parameters<F>): void => {
        window.clearTimeout(timeoutId);
        timeoutId = window.setTimeout(() => {
            callback(...args);
        }, wait);
    };
};

export const capitalizeFirstLetter = (val: string): string => {
    return String(val).charAt(0).toUpperCase() + String(val).slice(1);
};

export const useToastMessages = (flash: FlashResponse, duration: number, translateFn: (key: string) => string): void => {
    const toastStore = useToastStore();

    if (flash?.message !== null && flash?.title !== null) {
        toastStore.pushToast({
            type: flash?.type,
            title: translateFn(flash.title),
            message: translateFn(flash.message),
            duration: duration,
        });
    }
};
