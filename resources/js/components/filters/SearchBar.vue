<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { SearchBarProps } from '@/definitions/interfaces';

defineProps<SearchBarProps>();
const model = defineModel();
</script>

<template>
    <div class="relative flex-grow">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <Search class="h-4 w-4 text-muted-foreground" />
        </div>
        <input class="w-full pl-10 pr-4 py-2 h-10 bg-background border border-input rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
            v-model="model"
            type="text" 
            :placeholder="placeholder" />
    </div>
</template>