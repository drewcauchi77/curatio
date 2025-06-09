<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Save, Eye, FileText, Trash2 } from 'lucide-vue-next';
import { capitalizeFirstLetter } from '@/helpers/helpers';
import { PublishMenuProps } from '@/definitions/interfaces';

const props = defineProps<PublishMenuProps>();

const emit = defineEmits(['handleUpdate']);

const handleSave = (): void => {
    if (props.createNew) {
        handleUpdate(2);
    } else if (props.status === 'published') {
        handleUpdate(1);
    } else if (props.status === 'draft') {
        handleUpdate(2);
    }
};

const handleMove = (): void => {
    if (props.status === 'deleted') {
        handleUpdate(1);
    } else {
        handleUpdate(3);
    }
};

const handleUpdate = (statusId: number = props.statusId): void => {
    emit('handleUpdate', statusId);
};

const saveActionName = computed((): string => {
    if (props.createNew) {
        return 'update-publish';
    } else if (props.status === 'published') {
        return 'draft';
    } else if (props.status === 'draft') {
        return 'publish';
    }

    return 'update';
});

const deleteActionName = computed((): string => {
    if (props.status === 'deleted') {
        return 'restore';
    }
    return 'delete';
});
</script>

<template>
    <div class="w-full px-4 pb-6 sm:px-6 lg:w-80 lg:px-0 lg:py-6 lg:pr-8">
        <div class="space-y-4">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-4 py-3">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                        <FileText class="h-4 w-4" />
                        {{ $t('actions.title') }}
                    </h3>
                </div>
                <div class="space-y-4 p-4">
                    <div class="flex flex-col gap-2 sm:flex-row lg:flex-col">
                        <Button
                            v-if="props.status !== 'deleted'"
                            variant="outline"
                            class="flex-1 cursor-pointer justify-center gap-2 text-sm hover:text-black lg:w-full"
                            @click="handleSave()"
                        >
                            <FileText class="h-4 w-4" />
                            {{ $t(`actions.${saveActionName}`) }}
                        </Button>
                        <Button
                            v-if="status !== ''"
                            variant="outline"
                            class="flex-1 cursor-pointer justify-center gap-2 text-sm hover:text-black lg:w-full"
                            disabled="true"
                        >
                            <Eye class="h-4 w-4" />
                            {{ $t('actions.preview') }}
                        </Button>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ $t('general.status') }}:</span>
                            <strong class="flex items-center gap-1">
                                {{ status !== '' ? capitalizeFirstLetter(status) : $t('general.not-available') }}
                            </strong>
                        </div>
                    </div>

                    <div class="mb-1 border-t border-gray-200 pt-2">
                        <Button
                            v-if="status !== ''"
                            variant="link"
                            class="with-svg flex cursor-pointer items-center gap-2 text-sm text-red-600 hover:text-red-700"
                            @click="handleMove()"
                        >
                            <Trash2 class="h-4 w-4" />
                            {{ $t(`actions.${deleteActionName}`) }}
                        </Button>
                    </div>

                    <div class="pt-2">
                        <Button class="inline-flex w-full cursor-pointer items-center gap-2" :disabled="isDisabled" @click="handleUpdate()">
                            <Save class="h-4 w-4" />
                            <strong>{{ $t(`actions.update`) }}</strong>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="css" scoped>
.with-svg {
    padding: 0 !important;
}
</style>
