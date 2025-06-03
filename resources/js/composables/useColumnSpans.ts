import type { Ref } from 'vue';
import { computed } from 'vue';

export function useColumnSpans(count: Ref<number>) {
    const colSpan = computed((): string => {
        return String(Math.floor(12 / count.value));
    });

    const lastColSpan = computed((): string => {
        const rem = 12 % count.value;
        return String(rem !== 0 ? Number(colSpan.value) + rem : Number(colSpan.value));
    });

    return { colSpan, lastColSpan };
}
