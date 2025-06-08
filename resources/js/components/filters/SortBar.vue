<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';

const model = defineModel();
</script>

<template>
  <div class="relative min-w-[200px]">
    <select
      v-model="model"
      class="bg-background border-input focus:ring-primary focus:border-primary h-10 w-full appearance-none rounded-md border py-2 pr-8 pl-4 text-sm focus:ring-2 focus:outline-none"
    >
      <option value="created_at|asc">
        Created At (Ascending)
      </option>
      <option value="created_at|desc">
        Created At (Descending)
      </option>
      <option value="title|asc">
        Title (Ascending)
      </option>
      <option value="title|desc">
        Title (Descending)
      </option>
    </select>

    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
      <ChevronDown class="text-muted-foreground h-4 w-4" />
    </div>
  </div>
</template>
