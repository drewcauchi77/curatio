<script setup lang="ts">
defineProps<{
    connected: boolean;
    authUrl: string;
}>();
</script>

<template>
    <div v-if="connected">
        <span>Connected</span>
    </div>
    <div v-else>
        <a :href="authUrl">Auth</a>
    </div>
</template>