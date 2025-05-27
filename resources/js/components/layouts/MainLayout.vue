<script setup lang="ts">
import { onMounted } from 'vue';
import { useStatusStore } from '@/store/status.store';
import ToastContainer from '../containers/ToastContainer.vue';
import SidebarMenu from '@/components/sidebar/SidebarMenu.vue';

const statusStore = useStatusStore();

onMounted(() => {
    statusStore.checkMobile();
});
</script>

<template>
    <main class="flex h-screen overflow-hidden">
        <SidebarMenu />
        <section 
            class="flex-grow px-2 mt-4 sm:px-5 overflow-y-auto transition-all duration-300"
            :class="[
                statusStore.isMobile ? 'ml-16' : (statusStore.isMenuCollapsed ? 'ml-16' : 'ml-64')
            ]"
        >
            <slot />
        </section>
    </main>
    <ToastContainer />
</template>