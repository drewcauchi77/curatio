<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import { PageHeaderProps } from '@/definitions/interfaces';

defineProps<PageHeaderProps>();
</script>

<template>
    <div class="border-b border-border sticky top-0 z-10 bg-background">
        <div class="container px-4 py-4 mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <Link :href="backLink" class="text-foreground opacity-80 hover:opacity-100 transition-colors">
                    <ChevronLeft class="h-5 w-5" />
                </Link>
                <h1 class="text-xl font-bold text-foreground">{{ title }}</h1>
            </div>

            <div class="flex">
                <slot></slot>
            </div>
        </div>
    </div>
</template>