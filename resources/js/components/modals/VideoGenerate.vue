<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Share2, ChevronLeft } from 'lucide-vue-next';
import { VideoGenerateProps } from '@/definitions/interfaces';
import ModalLayout from '../layouts/ModalLayout.vue';
// import ChannelInfo from '../channel/ChannelInfo.vue';

defineProps<VideoGenerateProps>();

const modalRef = ref<InstanceType<typeof ModalLayout>>();

const closeModal = () => {
    modalRef.value?.closeModal();
};

const disconnectForm = useForm({});

const handleDisconnect = (): void => {
    disconnectForm.delete('/modules/generate');
};
</script>

<template>
    <ModalLayout ref="modalRef" :title="$t('video-generate.title')" back-link="/modules">
        <template #main>
            <div class="text-foreground">
                <p class="mb-6">{{ $t('video-generate.description.line1') }}</p>
                <p class="mb-6">{{ $t('video-generate.description.line2') }}</p>
                <p>{{ $t('video-generate.description.line3') }}</p>
            </div>
            {{ connection.channelData }}
            <!-- <ChannelInfo v-if="channelData && connection.connected" :channel-info="channelData"></ChannelInfo> -->
        </template>
        <template #footer>
            <div class="flex justify-end gap-3">
                <Button variant="secondary" @click="closeModal()">
                    <ChevronLeft class="h-5 w-5" />
                    <strong>{{ $t('actions.go-back') }}</strong>
                </Button>
                <Button v-if="connection.connected" as-child class="ml-4" @click="handleDisconnect()">
                    <div class="inline-flex items-center gap-2">
                        <Share2 class="h-4 w-4" />
                        <strong class="hidden sm:block">Disconnect Account</strong>
                    </div>
                </Button>
                <Button v-else as-child class="ml-4">
                    <a :href="connection.authUrl" class="inline-flex items-center gap-2">
                        <Share2 class="h-4 w-4" />
                        <strong class="hidden sm:block">Connect Your Account</strong>
                    </a>
                </Button>
            </div>
        </template>
    </ModalLayout>
</template>

<style scoped>
.translate-x-full {
    transform: translateX(100%);
}
</style>
