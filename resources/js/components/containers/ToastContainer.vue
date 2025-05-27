<script setup lang="ts">
import { ToastClose, ToastDescription, ToastProvider, ToastRoot, ToastTitle, ToastViewport } from 'reka-ui'
import { computed } from 'vue'
import { useToastStore } from '@/store/toast.store'
import { Toast } from '@/definitions/interfaces';

const toastStore = useToastStore();
const toasts = computed(() => toastStore.toasts);

const getBgColor = (type: Toast['type']) => {
    switch (type) {
        case 'success': return 'bg-green-50 border-green-200'
        case 'error':   return 'bg-red-50   border-red-200'
        default:        return 'bg-white     border-slate-200'
    }
}

const getTextColor = (type: Toast['type']) => {
    switch (type) {
        case 'success': return 'text-green-800'
        case 'error':   return 'text-red-800'
        default:        return 'text-slate-800'
    }
}
</script>

<template>
    <ToastProvider>
        <ToastRoot v-for="toast in toasts" :key="toast.id" :duration="toast.duration"
            :class="[
                getBgColor(toast.type),
                'rounded-lg shadow-sm border p-4',
                'flex items-start justify-between gap-3',
                'data-[state=open]:animate-slideIn data-[state=closed]:animate-hide',
                'data-[swipe=move]:translate-x-[var(--reka-toast-swipe-move-x)]',
                'data-[swipe=cancel]:translate-x-0 data-[swipe=cancel]:transition-[transform_200ms_ease-out]',
                'data-[swipe=end]:animate-swipeOut',
            ]">
            <div class="flex items-start gap-3 flex-grow">
                <div class="flex flex-col">
                    <ToastTitle :class="[getTextColor(toast.type), 'font-medium text-sm']">
                        {{ toast.title }}
                    </ToastTitle>

                    <ToastDescription v-if="toast.description" class="text-sm opacity-90 mt-1">
                        {{ toast.description }}
                    </ToastDescription>
                </div>
            </div>

            <ToastClose class="cursor-pointer" :class="[getTextColor(toast.type), 'flex-shrink-0']" aria-label="Close" @click="toastStore.removeToast(toast.id)">
                <span aria-hidden="true">×</span>
            </ToastClose>
        </ToastRoot>

        <ToastViewport class="[--viewport-padding:_25px] fixed bottom-0 right-0 flex flex-col p-[var(--viewport-padding)] gap-[10px] w-[390px] max-w-[100vw] m-0 list-none z-[2147483647] outline-none" />
    </ToastProvider>
</template>