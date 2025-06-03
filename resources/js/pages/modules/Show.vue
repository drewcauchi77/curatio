<script setup lang="ts">
import Create from './Create.vue';
import { ModulesShowProps } from '@/definitions/interfaces';

defineProps<ModulesShowProps>();
</script>

<template>
    <Create :module="module" :is-edit="true" />
</template>
