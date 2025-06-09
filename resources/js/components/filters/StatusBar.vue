<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { StatusBarProps } from '@/definitions/interfaces';

defineProps<StatusBarProps>();

const emit = defineEmits<{
    status: [value: string];
}>();
</script>

<template>
    <div v-if="statuses !== null" class="border-border flex items-center gap-4 border-b px-2 pb-4 sm:gap-6 sm:px-4">
        <Button
            v-for="(status, index) in statuses"
            :key="index"
            variant="link"
            size="sm"
            class="cursor-pointer gap-0 p-0 text-sm font-medium no-underline transition-colors hover:no-underline"
            :class="[
                currentStatus === status.value ? 'text-foreground hover:text-foreground' : 'text-muted-foreground/60 hover:text-muted-foreground',
            ]"
            @click="emit('status', status.value)"
        >
            <span>{{ status.label }}</span>
            <span class="ml-1 cursor-pointer text-xs no-underline opacity-70 hover:no-underline">({{ status.count }})</span>
        </Button>
    </div>
</template>
