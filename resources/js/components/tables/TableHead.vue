<script setup lang="ts">
import { TableHeaderProps } from '@/definitions/interfaces';

defineProps<TableHeaderProps>();
</script>

<template>
    <div class="rounded-t-lg border-b border-border bg-card">
        <div class="grid grid-cols-12 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
            <div v-for="(heading, index) in headings" class="flex items-center gap-2" :class="{ 'justify-self-end' : index == heading.length - 1}"
                :style="`grid-column: span ${sizes[index]} / span ${sizes[index]};`" :key="index">
                {{ heading }}
            </div>
        </div>
    </div>
</template>