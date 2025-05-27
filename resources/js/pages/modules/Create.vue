<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useForm, InertiaForm } from "@inertiajs/vue3";
import type { CreateModuleForm } from "@/definitions/types";
import { Button } from "@/components/ui/button";
import InputField from "@/components/global/InputField.vue";
import PageHeader from "@/components/global/PageHeader.vue";
import { Save, Text, BookOpen } from 'lucide-vue-next';
import { ModulesCreateProps } from "@/definitions/interfaces";
import PublishMenu from "@/components/sidebar/PublishMenu.vue";

const isEdit = ref<boolean>(false);

const props = defineProps<ModulesCreateProps>();

onMounted((): void => {
    if (props.module) isEdit.value = true;
});

const isLoading = ref<boolean>(false);

const moduleForm: InertiaForm<CreateModuleForm> = useForm({
    title: props.module?.title || '',
    description: props.module?.description || '',
    status_id: props.module?.status_id,
});

const handleSubmit = (newValue: number): void => {
    isLoading.value = true;
    moduleForm.status_id = newValue;
    
    if (isEdit.value && props.module?.id) {
        moduleForm.put(`/modules/${props.module.id}`, {
            onFinish: (): boolean => isLoading.value = false,
        });
    } else {
        moduleForm.post('/modules/create', {
            onFinish: (): boolean => isLoading.value = false,
        });
    }
};

const isDataUpdated = (): boolean => {
    return props.module?.title !== moduleForm.title || props.module?.description !== moduleForm.description;
};
</script>

<template>
    <div class="min-h-screen">
        <div class="bg-white sticky top-0 z-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <PageHeader :back-link="'/modules'" :title="isEdit ? module?.title : $t('modules.create-title')" class="border-0 bg-transparent p-0">
                    <div class="flex items-center gap-2 lg:hidden">
                        <Button :disabled="isLoading" class="cursor-pointer inline-flex items-center gap-2">
                            <Save class="h-4 w-4" />
                            <strong>{{ $t(`actions.${isEdit ? 'update' : 'publish'}`) }}</strong>
                        </Button>
                    </div>
                </PageHeader>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row max-w-7xl mx-auto">
            <div class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <form @submit.prevent class="p-6 space-y-6">
                        <div class="space-y-2">
                            <InputField 
                                input-name="title" 
                                label-name="Module Title" 
                                input-type="text" 
                                :placeholder="$t('modules.placeholders.module-title')" 
                                v-model="moduleForm.title"
                                class="text-lg font-medium"
                            >
                                <BookOpen class="h-5 w-5" />
                            </InputField>
                        </div>
                        
                        <div class="space-y-2">
                            <InputField 
                                input-name="description" 
                                label-name="Module Description" 
                                input-type="textarea" 
                                :placeholder="$t('modules.placeholders.module-description')" 
                                v-model="moduleForm.description"
                                class="min-h-[200px]"
                            >
                                <Text class="h-5 w-5" />
                            </InputField>
                        </div>
                    </form>
                </div>
            </div>

            <PublishMenu :status="props.module?.status_slug ?? ''" 
                :status-id="props.module?.status_id ?? 1" 
                :isDisabled="!isDataUpdated()"
                :create-new="isEdit ? false : true"
                @handleUpdate="handleSubmit" />
        </div>
    </div>
</template>