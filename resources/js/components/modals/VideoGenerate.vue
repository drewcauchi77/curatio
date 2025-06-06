<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { X } from 'lucide-vue-next';
import { useForm, router, Link } from '@inertiajs/vue3';
import { useStatusStore } from '@/store/status.store';
import { Button } from '@/components/ui/button';
import { Share2 } from 'lucide-vue-next';
import { VideoGenerateProps } from '@/definitions/interfaces';

const statusStore = useStatusStore();
const isVisible = ref(false);

defineProps<VideoGenerateProps>();

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
        },
    });
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-end bg-black/50 opacity-100 transition-opacity duration-300">
        <div class="absolute inset-0" @click="closeModal"></div>

        <div
            class="bg-background relative h-full w-full max-w-[97%] transform overflow-y-auto shadow-xl transition-all duration-300 ease-in-out sm:max-w-[550px]"
            :class="isVisible ? 'translate-x-0' : 'translate-x-full'"
        >
            <div class="border-border bg-background sticky top-0 z-10 flex items-center justify-between border-b p-4">
                <h2 class="text-foreground text-xl font-semibold">{{ title }}</h2>
                <button
                    type="button"
                    @click="closeModal"
                    class="text-foreground-light focus:ring-primary cursor-pointer rounded-full p-1 opacity-80 hover:opacity-100 focus:ring-2 focus:outline-none"
                >
                    <X class="h-6 w-6" />
                </button>
            </div>

            <div class="p-6">
                <slot>
                    <div class="space-y-6">
                        <p class="text-foreground">
                            Connect your Youtube account so you can automatically generate modules based on the videos in your channel.
                        </p>
                    </div>
                </slot>
            </div>

            <!-- Modal footer -->
            <div class="border-border bg-background sticky bottom-0 border-t p-4">
                <div class="flex justify-end gap-3" v-if="connection.connected">
                    <span>Already Connected</span>
                </div>
                <div class="flex justify-end gap-3" v-else>
                    <Button variant="outline" @click="closeModal">Cancel</Button>
                    <Button as-child class="ml-4" :disabled="channelForm.processing">
                        <a :href="connection.authUrl" class="inline-flex items-center gap-2">
                            <Share2 class="h-4 w-4" />
                            <strong class="hidden sm:block">Connect Your Account</strong>
                        </a>
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
