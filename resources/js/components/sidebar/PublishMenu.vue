<script setup lang="ts">
import { computed } from "vue";
import { Button } from "@/components/ui/button";
import { Save, Eye, FileText, Trash2 } from 'lucide-vue-next';
import { capitalizeFirstLetter } from '@/helpers/helpers';
import { PublishMenuProps } from "@/definitions/interfaces";

const props = defineProps<PublishMenuProps>()

const emit = defineEmits(['handleUpdate'])

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
    emit('handleUpdate', statusId)
};

const saveActionName = computed((): string => {
    if (props.createNew) {
        return 'update-publish';
    } else if (props.status === 'published') {
        return 'draft';
    } else if (props.status === 'draft') {
        return 'publish';
    } 
});

const deleteActionName = computed((): string => {
    if (props.status === 'deleted') {
        return 'restore';
    }
    return 'delete';
});
</script>

<template>
    <div class="w-full lg:w-80 px-4 sm:px-6 pb-6 lg:py-6 lg:px-0 lg:pr-8">
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <FileText class="h-4 w-4" />
                        {{ $t('actions.title') }}
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex flex-col sm:flex-row lg:flex-col gap-2">
                        <Button @click="handleSave()" v-if="props.status !== 'deleted'" variant="outline" class="flex-1 lg:w-full justify-center gap-2 text-sm cursor-pointer hover:text-black">
                            <FileText class="h-4 w-4" />
                            {{ $t(`actions.${saveActionName}`) }}
                        </Button>
                        <Button v-if="status !== ''" variant="outline" class="flex-1 lg:w-full justify-center gap-2 text-sm cursor-pointer hover:text-black" disabled="true">
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

                    <div class="pt-2 border-t border-gray-200 mb-1">
                        <Button v-if="status !== ''" variant="link" @click="handleMove()" class="with-svg cursor-pointer text-red-600 hover:text-red-700 text-sm flex items-center gap-2">
                            <Trash2 class="h-4 w-4" />
                            {{ $t(`actions.${deleteActionName}`) }}
                        </Button>
                    </div>

                    <div class="pt-2">
                        <Button class="w-full cursor-pointer inline-flex items-center gap-2" :disabled="isDisabled" @click="handleUpdate()">
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