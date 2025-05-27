<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import { LayoutDashboard, GraduationCap, BookOpen, Settings, User, LogOut, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { useStatusStore } from '@/store/status.store';
import SidebarItem from './SidebarItem.vue';

const statusStore = useStatusStore();
const checkMobile = () => {
    statusStore.checkMobile();
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});

const toggleSidebar = (): void => {
    statusStore.setIsMenuCollapsed(!statusStore.isMenuCollapsed);
};
</script>

<template>
    <div class="sidebar-container fixed h-screen flex flex-col border-r border-border bg-card transition-all duration-300 ease-in-out"
        :class="[
            statusStore.isMobile ? 'w-16' : (statusStore.isMenuCollapsed ? 'w-16' : 'w-64')
        ]">
        <nav class="flex-1 px-2 py-4 space-y-1">
            <SidebarItem href="/dashboard" name="Dashboard" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile">
                <LayoutDashboard class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>

            <SidebarItem href="/courses" name="Courses" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile">
                <GraduationCap class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>

            <SidebarItem href="/modules" name="Modules" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile">
                <BookOpen class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>

            <SidebarItem href="/settings" name="Settings" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile">
                <Settings class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>
        </nav>

        <div class="p-2 border-t border-border">
            <SidebarItem href="/profile" name="User" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile">
                <User class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>

            <SidebarItem href="/logout" name="Logout" :collapsed="statusStore.isMenuCollapsed || statusStore.isMobile" method="post" as="button" :isDestructive="true" class="cursor-pointer">
                <LogOut class="h-5 w-5 flex-shrink-0" />
            </SidebarItem>
            
            <button 
                v-if="!statusStore.isMobile"
                @click="toggleSidebar" 
                class="cursor-pointer flex items-center w-full px-2 py-3 rounded-lg bg-muted hover:bg-accent hover:text-accent-foreground transition-colors overflow-hidden mb-2"
                :class="{ 'justify-center': statusStore.isMenuCollapsed }"
            >
                <div class="flex-shrink-0">
                    <ChevronLeft v-if="!statusStore.isMenuCollapsed" class="h-5 w-5" />
                    <ChevronRight v-else class="h-5 w-5" />
                </div>
                <span 
                    class="text-sm font-medium whitespace-nowrap transition-all duration-300"
                    :class="statusStore.isMenuCollapsed 
                        ? 'w-0 opacity-0 ml-0' 
                        : 'w-auto opacity-100 ml-3'"
                >
                    Collapse
                </span>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* All styles are now handled via reactivity */
</style>
