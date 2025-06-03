<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { PaginationResponse } from '@/definitions/interfaces';

interface Props {
    pagination: PaginationResponse;
    showLinks?: number;
    preserveQuery?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showLinks: 1, // <-- only one page before/after
    preserveQuery: true,
});

const pageRange = computed<(number | string)[]>(() => {
    const total = props.pagination.last_page;
    const current = props.pagination.current_page;
    const n = props.showLinks;

    const pages: (number | string)[] = [1];

    if (current - n > 2) pages.push('...');

    const start = Math.max(2, current - n);
    const end = Math.min(total - 1, current + n);

    for (let p = start; p <= end; p++) {
        pages.push(p);
    }

    if (current + n < total - 1) pages.push('...');
    if (total > 1) pages.push(total);

    return pages;
});

const getPageUrl = (page: number): string => {
    if (!props.pagination.path) return '#';
    const url = new URL(props.pagination.path, window.location.origin);
    const params = new URLSearchParams(props.preserveQuery ? window.location.search : '');

    params.set('page', String(page));
    return `${url.pathname}?${params.toString()}`;
};
</script>

<template>
    <div class="my-4 flex items-center justify-center gap-1 select-none" v-if="pagination.last_page > 1">
        <Link
            :href="pagination.current_page === 1 ? '#' : getPageUrl(pagination.current_page - 1)"
            class="flex items-center justify-center rounded-md p-2 transition-colors"
            :class="pagination.current_page === 1 ? 'pointer-events-none text-gray-300 opacity-50' : 'text-gray-400 hover:text-black'"
        >
            <ChevronLeft class="h-4 w-4" />
        </Link>

        <template v-for="page in pageRange" :key="page">
            <span v-if="page === '...'" class="px-2 text-sm text-gray-400">
                {{ page }}
            </span>

            <span
                v-else-if="page === pagination.current_page"
                class="flex h-8 min-w-[2rem] cursor-default items-center justify-center rounded-md text-sm font-bold text-black"
            >
                {{ page }}
            </span>

            <Link
                v-else
                :href="getPageUrl(page as number)"
                class="flex h-8 min-w-[2rem] items-center justify-center rounded-md text-sm text-gray-400 transition-colors hover:text-black"
            >
                {{ page }}
            </Link>
        </template>

        <Link
            :href="pagination.current_page === pagination.last_page ? '#' : getPageUrl(pagination.current_page + 1)"
            class="flex items-center justify-center rounded-md p-2 transition-colors"
            :class="
                pagination.current_page === pagination.last_page ? 'pointer-events-none text-gray-300 opacity-50' : 'text-gray-400 hover:text-black'
            "
        >
            <ChevronRight class="h-4 w-4" />
        </Link>
    </div>
</template>
