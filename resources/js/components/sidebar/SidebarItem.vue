<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { SidebarProps } from "@/definitions/interfaces";

withDefaults(defineProps<SidebarProps>(), {
    method: "get",
    as: "a",
    isDestructive: false
});
</script>

<template>
    <Link :href="href" :method="method" :as="as" 
        class="flex items-center w-full px-2 py-3 rounded-lg transition-colors overflow-hidden mb-2"
        :class="{
            'justify-center': collapsed,
            'text-foreground hover:bg-accent hover:text-accent-foreground': !isDestructive,
            'text-destructive hover:bg-destructive/10': isDestructive
        }">
        <div class="flex-shrink-0">
            <slot></slot>
        </div>
        <span class="text-sm font-medium whitespace-nowrap transition-all duration-300"
            :class="collapsed ? 'w-0 opacity-0 ml-0' : 'w-auto opacity-100 ml-3'">
            {{ name }}
        </span>
    </Link>
</template>
