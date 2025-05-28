<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, Transition } from 'vue';
import { X } from 'lucide-vue-next';
import { useForm, router, Link } from '@inertiajs/vue3';
import { useStatusStore } from '@/store/status.store';
import { Button } from '@/components/ui/button';

const statusStore = useStatusStore();
const isVisible = ref(false);

defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Generate Video'
    }
});

const closeModal = () => {
    isVisible.value = false;
    setTimeout(() => {
        router.visit('/modules');
    }, 300);
};

const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        closeModal();
    }
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

const channelForm = useForm({
    channel: '',
});

const handleSubmit = () => {
    channelForm.post('/modules/auth', {
        onSuccess: () => {
            closeModal();
        }
    });
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-end transition-opacity duration-300 bg-black/50 opacity-100">
        <div class="absolute inset-0" @click="closeModal"></div>
        
        <div class="relative h-full w-full max-w-[97%] sm:max-w-[550px] overflow-y-auto bg-background shadow-xl transition-all duration-300 ease-in-out transform"
            :class="isVisible ? 'translate-x-0' : 'translate-x-full'">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-background p-4">
                <h2 class="text-xl font-semibold text-foreground">{{ title }}</h2>
                <button 
                type="button" 
                @click="closeModal" 
                class="rounded-full p-1 text-foreground-light opacity-80 hover:opacity-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
                >
                <X class="h-6 w-6" />
                </button>
            </div>
            
            <div class="p-6">
                <slot>
                <div class="space-y-6">
                    <p class="text-foreground">Connect your Youtube account so you can automatically generate modules based on the videos in your channel.</p>
                </div>
                </slot>
            </div>
            
            <!-- Modal footer -->
            <div class="sticky bottom-0 border-t border-border bg-background p-4">
                <div class="flex justify-end gap-3">
                <Button
                    variant="outline"
                    @click="closeModal"
                >
                    Cancel
                </Button>
                <Button
                    @click="handleSubmit"
                    :disabled="channelForm.processing"
                >
                    Connect your Youtube Account
                </Button>

                
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.translate-x-full {
    transform: translateX(100%);
}
</style>