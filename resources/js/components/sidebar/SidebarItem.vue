<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { SidebarProps } from '@/definitions/interfaces';

withDefaults(defineProps<SidebarProps>(), {
    method: 'get',
    as: 'a',
    isDestructive: false,
});
</script>

<template>
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="mb-2 flex w-full items-center overflow-hidden rounded-lg px-2 py-3 transition-colors"
        :class="{
            'justify-center': collapsed,
            'text-foreground hover:bg-accent hover:text-accent-foreground': !isDestructive,
            'text-destructive hover:bg-destructive/10': isDestructive,
        }"
    >
        <div class="flex-shrink-0">
            <slot></slot>
        </div>
        <span
            class="text-sm font-medium whitespace-nowrap transition-all duration-300"
            :class="collapsed ? 'ml-0 w-0 opacity-0' : 'ml-3 w-auto opacity-100'"
        >
            {{ name }}
        </span>
    </Link>
</template>
