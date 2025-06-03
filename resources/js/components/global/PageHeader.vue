<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { PageHeaderProps } from '@/definitions/interfaces';

defineProps<PageHeaderProps>();
</script>

<template>
    <div class="border-border bg-background sticky top-0 z-10 border-b">
        <div class="container mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-3">
                <Link :href="backLink" class="text-foreground opacity-80 transition-colors hover:opacity-100">
                    <ChevronLeft class="h-5 w-5" />
                </Link>
                <h1 class="text-foreground text-xl font-bold">{{ title }}</h1>
            </div>

            <div class="flex">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
