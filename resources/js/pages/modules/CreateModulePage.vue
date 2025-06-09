<script setup lang="ts">
import { onMounted, ref, getCurrentInstance, computed } from 'vue';
import { useForm, InertiaForm } from '@inertiajs/vue3';
import { CreateModuleForm } from '@/definitions/types';
import { Button } from '@/components/ui/button';
import InputField from '@/components/global/InputField.vue';
import PageHeader from '@/components/global/PageHeader.vue';
import { Save, Text, BookOpen } from 'lucide-vue-next';
import { ComponentErrorData, ModuleComponentData, ModulesCreateProps } from '@/definitions/interfaces';
import PublishMenu from '@/components/sidebar/PublishMenu.vue';
import { errorBagToToastMessages, useToastMessages } from '@/helpers/helpers';

const props = defineProps<ModulesCreateProps>();

const isEdit = ref<boolean>(false);
const isLoading = ref<boolean>(false);

const instance = getCurrentInstance();
const proxy = instance?.proxy;

onMounted((): void => {
    if (props.module) isEdit.value = true;
});

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
            onFinish: (): boolean => (isLoading.value = false),
            onSuccess: (data) => {
                const moduleData = data as unknown as ModuleComponentData;
                useToastMessages(moduleData.props.flash, 4000, proxy.$t);
            },
            onError: (errors) => {
                const errorData = errors as unknown as ComponentErrorData;
                errorBagToToastMessages(errorData, 4000, proxy.$t);
            },
        });
    } else {
        moduleForm.post('/modules/create', {
            onFinish: (): boolean => (isLoading.value = false),
            onSuccess: (data) => {
                const moduleData = data as unknown as ModuleComponentData;
                useToastMessages(moduleData.props.flash, 4000, proxy.$t);
            },
            onError: (errors) => {
                const errorData = errors as unknown as ComponentErrorData;
                errorBagToToastMessages(errorData, 4000, proxy.$t);
            },
        });
    }
};

const isDataUpdated = (): boolean => {
    return props.module?.title !== moduleForm.title || props.module?.description !== moduleForm.description;
};

const metaTitle = computed(() => {
    return isEdit.value && props.module?.id ? proxy.$t('metatags.modules/Show', ['title', props.module?.title]) : proxy.$t('metatags.modules/Create');
});
</script>

<template>
    <div class="min-h-screen">
        <MetaTags :title="metaTitle"></MetaTags>
        <div class="sticky top-0 z-10 bg-white">
            <div class="px-4 sm:px-6 lg:px-8">
                <PageHeader :back-link="'/modules'" :title="isEdit ? module?.title : $t('modules.create-title')" class="border-0 bg-transparent p-0">
                    <div class="flex items-center gap-2 lg:hidden">
                        <Button :disabled="isLoading" class="inline-flex items-center gap-2">
                            <Save class="h-4 w-4" />
                            <strong>{{ $t(`actions.${isEdit ? 'update' : 'publish'}`) }}</strong>
                        </Button>
                    </div>
                </PageHeader>
            </div>
        </div>

        <div class="mx-auto flex max-w-7xl flex-col lg:flex-row">
            <div class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                    <form class="space-y-6 p-6" @submit.prevent>
                        <div class="space-y-2">
                            <InputField
                                v-model="moduleForm.title"
                                input-name="title"
                                label-name="Module Title"
                                input-type="text"
                                :placeholder="$t('modules.placeholders.module-title')"
                                class="text-lg font-medium"
                            >
                                <BookOpen class="h-5 w-5" />
                            </InputField>
                        </div>

                        <div class="space-y-2">
                            <InputField
                                v-model="moduleForm.description"
                                input-name="description"
                                label-name="Module Description"
                                input-type="textarea"
                                :placeholder="$t('modules.placeholders.module-description')"
                                class="min-h-[200px]"
                            >
                                <Text class="h-5 w-5" />
                            </InputField>
                        </div>
                    </form>
                </div>
            </div>

            <PublishMenu
                :status="props.module?.status_slug ?? ''"
                :status-id="props.module?.status_id ?? 1"
                :is-disabled="!isDataUpdated()"
                :create-new="isEdit ? false : true"
                @handle-update="handleSubmit"
            />
        </div>
    </div>
</template>
