<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Share2, ChevronLeft, Users, Video, Eye } from 'lucide-vue-next';
import { VideoGenerateProps } from '@/definitions/interfaces';
import ModalLayout from '../layouts/ModalLayout.vue';
import ChannelInfo from '../channel/ChannelInfo.vue';
import { channel } from 'diagnostics_channel';

defineProps<VideoGenerateProps>();

const modalRef = ref<InstanceType<typeof ModalLayout>>();

const channelForm = useForm({
    channel: '',
});

const handleSubmit = () => {
    channelForm.post('/modules/auth', {
        onSuccess: () => {
        },
    });
};

const closeModal = () => {
    modalRef.value?.closeModal();
};
</script>

<template>
    <ModalLayout ref="modalRef" :title="$t('video-generate.title')" back-link="/modules">
        <template v-slot:main>
            <p class="text-foreground" v-html="$t('video-generate.description')"></p>

            <ChannelInfo v-if="channelData && connection.connected" :channel-info="channelData"></ChannelInfo>
        </template>
        <template v-slot:footer>
            <div class="flex justify-end gap-3">
                <Button variant="secondary" @click="closeModal()">
                    <ChevronLeft class="h-5 w-5" />
                    <strong>{{ $t('actions.go-back') }}</strong>
                </Button>
                <Button as-child class="ml-4" :disabled="channelForm.processing">
                    <div v-if="connection.connected" class="inline-flex items-center gap-2">
                        <Share2 class="h-4 w-4" />
                        <strong class="hidden sm:block">Disconnect Account</strong>
                    </div>
                    <a :href="connection.authUrl" v-else class="inline-flex items-center gap-2">
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
