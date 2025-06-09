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
                <h2 class="text-foreground text-2xl font-bold tracking-tight">Curatio</h2>
                <p v-if="props.subTitle !== ''" class="text-foreground-light text-sm">
                    {{ props.subTitle }}
                </p>
            </div>

            <div class="bg-card overflow-hidden rounded-2xl shadow-xl">
                <slot name="main" />
            </div>

            <slot name="footer" />
        </div>
    </div>
</template>
