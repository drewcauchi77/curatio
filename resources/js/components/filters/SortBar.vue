<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';

const model = defineModel();
</script>

<template>
    <div class="relative min-w-[200px]">
        <select class="appearance-none w-full bg-background border border-input rounded-md pl-4 pr-8 py-2 h-10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm" 
            v-model="model">
            <option value="created_at|asc">Created At (Ascending)</option>
            <option value="created_at|desc">Created At (Descending)</option>
            <option value="title|asc">Title (Ascending)</option>
            <option value="title|desc">Title (Descending)</option>
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <ChevronDown class="h-4 w-4 text-muted-foreground" />
        </div>
    </div>
</template>