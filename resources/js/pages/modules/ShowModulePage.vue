<script setup lang="ts">
import CreateModulePage from './CreateModulePage.vue';
import { ModulesShowProps } from '@/definitions/interfaces';

defineProps<ModulesShowProps>();
</script>

<template>
    <CreateModulePage :module="module" is-edit />
</template>
