<script setup lang="ts">
import { getCurrentInstance, onMounted } from 'vue';
import Create from './Create.vue';
import { ModulesShowProps } from '@/definitions/interfaces';
import { useToastStore } from '@/store/toast.store';

const props = defineProps<ModulesShowProps>();

const toastStore = useToastStore();
const { proxy } = getCurrentInstance()!;

onMounted((): void => {
    if (props.flash?.success && props.flash?.message !== null) {
        toastStore.pushToast({
            type: 'success',
            title: proxy.$t('success.success'),
            description: proxy.$t(props.flash.message),
            duration: 4000,
        });
    }
});
</script>

<template>
    <Create :module="module" :is-edit="true" />
</template>