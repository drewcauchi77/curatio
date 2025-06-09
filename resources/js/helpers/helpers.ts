import { FlashResponse } from '@/definitions/interfaces';
import { ToastType } from '@/definitions/types';
import { useToastStore } from '@/store/toast.store';

// eslint-disable-next-line no-unused-vars
export const debounce = <F extends (...args: Parameters<F>) => void>(callback: F, wait: number): ((...args: Parameters<F>) => void) => {
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

// eslint-disable-next-line no-unused-vars
export const useToastMessages = (flash: FlashResponse, duration: number, translateFn: (key: string) => string = undefined): void => {
    const toastStore = useToastStore();

    if (flash?.message !== null && flash?.title !== null) {
        toastStore.pushToast({
            type: flash?.type,
            title: translateFn !== undefined ? translateFn(flash.title) : flash.title,
            message: translateFn !== undefined ? translateFn(flash.message) : flash.message,
            duration,
        });
    }
};

// eslint-disable-next-line no-unused-vars
export const errorBagToToastMessages = (errors: Object, duration: number, translateFn: (key: string, ...params: [string, string][]) => string) => {
    Object.values(errors).forEach((element: string) => {
        const splitElement = element.split('|');
        const key = splitElement[0];
        const values = splitElement.length > 1 ? splitElement[1].split(',') : [];
        let replacementValues: [string, string][] = [];

        values.forEach((value, index) => {
            replacementValues = [...replacementValues, [`field${index + 1}`, value]];
        });

        const errorData: FlashResponse = {
            type: 'error' as ToastType,
            title: translateFn('errors.error'),
            message: translateFn(key, ...replacementValues),
        };

        useToastMessages(errorData, duration);
    });
};
