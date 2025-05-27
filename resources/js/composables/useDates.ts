import { ref } from 'vue';

export function useCurrentYear() {
    const currentYear = ref(new Date().getFullYear());
    return { currentYear };
}