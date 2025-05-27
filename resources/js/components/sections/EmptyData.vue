<script setup lang="ts">
import { EmptyDataProps } from '@/definitions/interfaces';
import { ImageIcon } from 'lucide-vue-next';

defineProps<EmptyDataProps>();
</script>

<template>
    <div class="bg-card rounded-b-lg p-8 text-center border border-border">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mb-4">
            <ImageIcon class="h-6 w-6 text-primary" />
        </div>

        <h3 class="text-lg font-semibold">{{ title }}</h3>
    </div>
</template>