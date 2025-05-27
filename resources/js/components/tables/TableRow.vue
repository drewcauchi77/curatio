<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Eye } from 'lucide-vue-next';
import { TableRowProps } from '@/definitions/interfaces';

defineProps<TableRowProps>();
</script>

<template>
    <div class="border-b border-border last:border-b-0 transition-colors">
        <div class="grid grid-cols-12 items-center py-4 px-6 gap-0">
            <div v-for="(item, index) in items" class="flex items-center gap-2" :style="`grid-column: span ${sizes[index]} / span ${sizes[index]};`" :key="index">
                <h3 v-if="index == 0" class="font-medium text-foreground text-sm">{{ item }}</h3>
                <p v-else class="text-sm text-muted-foreground line-clamp-2">{{ item }}</p>
            </div>
            
            <div class="flex justify-end gap-2 mt-0" :style="`grid-column: span ${sizes[items.length]} / span ${sizes[items.length]};`">
                <Button size="icon" as-child class="w-20">
                    <Link :href="link">
                        <strong>{{ viewTitle }}</strong>
                        <Eye class="h-4 w-4" />
                    </Link>
                </Button>
            </div>
        </div>
    </div>
</template>