<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { SearchBarProps } from '@/definitions/interfaces';

defineProps<SearchBarProps>();
const model = defineModel<string>();
</script>

<template>
    <div class="relative flex-grow">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <Search class="text-muted-foreground h-4 w-4" />
        </div>
        <input
            v-model="model"
            class="bg-background border-input focus:ring-primary focus:border-primary h-10 w-full rounded-md border py-2 pr-4 pl-10 text-sm focus:ring-2 focus:outline-none"
            type="text"
            :placeholder="placeholder"
        />
    </div>
</template>
