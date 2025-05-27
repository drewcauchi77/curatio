<script setup lang="ts">
const props = defineProps({
    subTitle: {
        type: String,
        default: () => '',
    },
});
</script>

<template>
    <div class="flex min-h-screen w-full items-center justify-center">
        <div class="w-full max-w-md space-y-8 p-2">
            <div class="flex flex-col items-center space-y-2">
                <h2 class="text-2xl font-bold tracking-tight text-foreground">EduCli</h2>
                <p class="text-sm text-foreground-light" v-if="props.subTitle !== ''">{{ props.subTitle }}</p>
            </div>

            <div class="overflow-hidden rounded-2xl bg-card shadow-xl">
                <slot name="main"></slot>
            </div>

            <slot name="footer"></slot>
        </div>
    </div>
</template>