<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Zap, ZapOff, RefreshCw } from 'lucide-vue-next';
import { VideoGenerateProps } from '@/definitions/interfaces';
import ModalLayout from '../layouts/ModalLayout.vue';
import ChannelInfo from '../channel/ChannelInfo.vue';

defineProps<VideoGenerateProps>();

const modalRef = ref<InstanceType<typeof ModalLayout>>();

const disconnectForm = useForm({});

const handleDisconnect = (): void => {
    disconnectForm.delete('/modules/youtube');
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

            <ChannelInfo v-if="channelData && connection.connected" :channel-info="channelData"></ChannelInfo>
        </template>
        <template #footer>
            <div class="flex justify-end gap-3">
                <template v-if="connection.connected">
                    <Button as-child variant="secondary" @click="handleDisconnect()">
                        <div class="inline-flex items-center gap-2">
                            <ZapOff class="h-4 w-4" />
                            <strong class="hidden sm:block">Disconnect Account</strong>
                        </div>
                    </Button>
                    <Button as-child class="ml-2" @click="handleDisconnect()">
                        <div class="inline-flex items-center gap-2">
                            <RefreshCw class="h-4 w-4" />
                            <strong class="hidden sm:block">Sync Videos</strong>
                        </div>
                    </Button>
                </template>
                <Button v-else as-child>
                    <a :href="connection.authUrl" class="inline-flex items-center gap-2">
                        <Zap class="h-4 w-4" />
                        <strong class="hidden sm:block">Connect Your Account</strong>
                    </a>
                </Button>
            </div>
        </template>
    </ModalLayout>
</template>
