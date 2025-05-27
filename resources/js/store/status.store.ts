import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useStatusStore = defineStore('status', () => {
    const isMenuCollapsed = ref(false);
    const isMobile = ref(false);
    const isBodyScrollable = ref(true);

    function setIsMenuCollapsed(newValue: boolean) {
        isMenuCollapsed.value = newValue;
    }

    function setIsMobile(newValue: boolean) {
        isMobile.value = newValue;
    }

    function setIsBodyScrollable(newValue: boolean) {
        isBodyScrollable.value = newValue;
    }

    function checkMobile() {
        setIsMobile(window.innerWidth < 768);
    }

    return {
        isMenuCollapsed,
        isMobile,
        isBodyScrollable,
        setIsMenuCollapsed,
        setIsMobile,
        setIsBodyScrollable,
        checkMobile
    };
});
