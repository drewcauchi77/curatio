<script setup lang="ts">
</script>

<template>
    <div class="overflow-auto">
        <div class="min-w-2xl">
            <slot name="head"></slot>
        
            <div class="bg-background rounded-t-none border-border border-t-0">
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>