<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Eye } from 'lucide-vue-next';
import { TableRowProps } from '@/definitions/interfaces';

defineProps<TableRowProps>();
</script>

<template>
    <div class="border-border border-b transition-colors last:border-b-0">
        <div class="grid grid-cols-12 items-center gap-0 px-6 py-4">
            <div
                v-for="(item, index) in items"
                :key="index"
                class="flex items-center gap-2"
                :style="`grid-column: span ${sizes[index]} / span ${sizes[index]};`"
            >
                <h3 v-if="index == 0" class="text-foreground text-sm font-medium">{{ item }}</h3>
                <p v-else class="text-muted-foreground line-clamp-2 text-sm">{{ item }}</p>
            </div>

            <div class="mt-0 flex justify-end gap-2" :style="`grid-column: span ${sizes[items.length]} / span ${sizes[items.length]};`">
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
