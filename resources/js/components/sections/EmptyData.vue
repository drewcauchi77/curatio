<script setup lang="ts">
import { EmptyDataProps } from '@/definitions/interfaces';
import { ImageIcon } from 'lucide-vue-next';

defineProps<EmptyDataProps>();
</script>

<template>
    <div class="bg-card border-border rounded-b-lg border p-8 text-center">
        <div class="bg-primary/10 mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full">
            <ImageIcon class="text-primary h-6 w-6" />
        </div>

        <h3 class="text-lg font-semibold">{{ title }}</h3>
    </div>
</template>
