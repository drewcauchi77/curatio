<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { X } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { useStatusStore } from '@/store/status.store';
import { ModalLayoutProps } from '@/definitions/interfaces';

const props = defineProps<ModalLayoutProps>();

const statusStore = useStatusStore();
const isVisible = ref(false);

const closeModal = (): void => {
    isVisible.value = false;

    setTimeout(() => {
        router.visit(props.backLink);
    }, 300);
};

defineExpose({
    closeModal,
});

const handleKeyDown = (e: KeyboardEvent): void => {
    if (e.key === 'Escape') closeModal();
};

onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);
    statusStore.setIsBodyScrollable(false);

    setTimeout(() => {
        isVisible.value = true;
    }, 50);
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', handleKeyDown);
    statusStore.setIsBodyScrollable(true);
});
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-end bg-black/50 opacity-100 transition-opacity duration-300">
        <div class="absolute inset-0" @click="closeModal()"></div>

        <div
            class="bg-background relative h-full w-full max-w-[97%] transform overflow-y-auto shadow-xl transition-all duration-300 ease-in-out sm:max-w-[550px]"
            :class="isVisible ? 'translate-x-0' : 'translate-x-full'"
        >
            <div class="border-border bg-background sticky top-0 z-10 flex items-center justify-between border-b p-4">
                <h2 class="text-foreground text-xl font-semibold">{{ title }}</h2>
                <button
                    class="text-foreground-light focus:ring-primary cursor-pointer rounded-full p-1 opacity-80 hover:opacity-100 focus:ring-2 focus:outline-none"
                    type="button"
                    @click="closeModal()"
                >
                    <X class="h-6 w-6" />
                </button>
            </div>

            <div class="p-4">
                <slot name="main"></slot>
            </div>

            <div class="border-border bg-background sticky bottom-0 border-t p-4">
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.translate-x-full {
    transform: translateX(100%);
}
</style>
