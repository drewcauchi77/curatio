<script setup lang="ts">
import { TableHeaderProps } from '@/definitions/interfaces';

defineProps<TableHeaderProps>();
</script>

<template>
    <div class="border-border bg-card rounded-t-lg border-b">
        <div class="text-muted-foreground grid grid-cols-12 px-6 py-3 text-left text-xs font-medium tracking-wider uppercase">
            <div
                v-for="(heading, index) in headings"
                :key="index"
                class="flex items-center gap-2"
                :class="{ 'justify-self-end': index == heading.length - 1 }"
                :style="`grid-column: span ${sizes[index]} / span ${sizes[index]};`"
            >
                {{ heading }}
            </div>
        </div>
    </div>
</template>
