<script setup lang="ts">
import { ref } from 'vue';
import Create from './Create.vue';
import { ModulesShowProps } from '@/definitions/interfaces';
import { useForm, InertiaForm } from '@inertiajs/vue3';
import type { CreateModuleForm } from '@/definitions/types';
import { Button } from "@/components/ui/button";
import { Trash2, ArchiveRestore, AlertTriangle } from 'lucide-vue-next';

const props = defineProps<ModulesShowProps>();
const isLoading = ref<boolean>(false);

const moduleForm: InertiaForm<CreateModuleForm> = useForm({
    title: props.module?.title || '',
    description: props.module?.description || '',
    status_id: undefined
});

const changeStatus = (statusId: number): void => {
    isLoading.value = true;
    
    moduleForm.status_id = statusId;
    
    moduleForm.put(`/modules/${props.module.id}`, {
        onFinish: (): boolean => isLoading.value = false,
        onSuccess: (): void => {
            // Show toast
        }
    });
};
</script>

<template>
    <Create :module="module" :is-edit="true" />
</template>