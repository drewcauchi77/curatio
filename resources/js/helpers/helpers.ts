export const debounce = <F extends (...args: any[]) => void>(callback: F, wait: number): ((...args: Parameters<F>) => void) => {
    let timeoutId: ReturnType<typeof window.setTimeout>;
  
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