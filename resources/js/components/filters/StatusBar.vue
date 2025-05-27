<script setup lang="ts">
import { Button } from "@/components/ui/button";
import { StatusBarProps } from '@/definitions/interfaces';

defineProps<StatusBarProps>();

const emit = defineEmits<{
    'status': [value: string]
}>();
</script>

<template>
    <div class="flex items-center gap-4 sm:gap-6 border-b border-border pb-4 px-2 sm:px-4" v-if="statuses !== null">
        <Button 
            v-for="(status, index) in statuses" 
            :key="index"
            variant="link" 
            size="sm" 
            class="text-sm font-medium transition-colors cursor-pointer p-0 no-underline hover:no-underline gap-0"
            :class="[
                currentStatus === status.value 
                    ? 'text-foreground hover:text-foreground' 
                    : 'text-muted-foreground/60 hover:text-muted-foreground'
            ]"
            @click="emit('status', status.value)"
        >
            <span>{{ status.label }}</span>
            <span class="ml-1 text-xs opacity-70 cursor-pointer no-underline hover:no-underline">({{ status.count }})</span>
        </Button>
    </div>
</template>

