<script setup lang="ts">
import { MetaTagsProps } from '@/definitions/interfaces';
import { Head } from '@inertiajs/vue3';

defineProps<MetaTagsProps>();
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>
</template>
