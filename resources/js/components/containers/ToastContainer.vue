<script setup lang="ts">
import { ToastClose, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'reka-ui';
import { computed } from 'vue';
import { useToastStore } from '@/store/toast.store';
import { Toast } from '@/definitions/interfaces';

const toastStore = useToastStore();
const toasts = computed(() => toastStore.toasts);

const getBgColor = (type: Toast['type']) => {
    switch (type) {
        case 'success':
            return 'bg-green-50 border-green-200';
        case 'error':
            return 'bg-red-50   border-red-200';
        default:
            return 'bg-white     border-slate-200';
    }
};

const getTextColor = (type: Toast['type']) => {
    switch (type) {
        case 'success':
            return 'text-green-800';
        case 'error':
            return 'text-red-800';
        default:
            return 'text-slate-800';
    }
};
</script>

<template>
    <ToastProvider>
        <ToastRoot
            v-for="toast in toasts"
            :key="toast.id"
            :duration="toast.duration"
            class="data-[state=open]:animate-slideIn data-[state=closed]:animate-hide data-[swipe=end]:animate-swipeOut flex items-start justify-between gap-3 rounded-lg border p-4 shadow-sm data-[swipe=cancel]:translate-x-0 data-[swipe=cancel]:transition-[transform_200ms_ease-out] data-[swipe=move]:translate-x-[var(--reka-toast-swipe-move-x)]"
            :class="getBgColor(toast.type)"
        >
            <div class="flex flex-grow items-start gap-3">
                <div class="flex flex-col">
                    <ToastTitle class="text-sm font-medium" :class="getTextColor(toast.type)">
                        {{ toast.title }}
                    </ToastTitle>

                    <ToastDescription v-if="toast.message" class="mt-1 text-sm opacity-90">
                        {{ toast.message }}
                    </ToastDescription>
                </div>
            </div>

            <ToastClose
                class="flex-shrink-0 cursor-pointer"
                :class="getTextColor(toast.type)"
                aria-label="Close"
                @click="toastStore.removeToast(toast.id)"
            >
                <span aria-hidden="true">×</span>
            </ToastClose>
        </ToastRoot>

        <ToastViewport
            class="fixed right-0 bottom-0 z-[2147483647] m-0 flex w-[390px] max-w-[100vw] list-none flex-col gap-[10px] p-[var(--viewport-padding)] outline-none [--viewport-padding:_25px]"
        />
    </ToastProvider>
</template>
