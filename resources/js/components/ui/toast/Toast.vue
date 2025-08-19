<script setup lang="ts">
import { useToastProvider } from '@/composables/useToast'
import { ToastRoot, ToastTitle, ToastDescription, ToastAction, ToastProvider, ToastViewport } from 'reka-ui'
import { X } from 'lucide-vue-next'

const { toasts, removeToast } = useToastProvider()

</script>

<template>
    <ToastProvider swipe-direction="right">
        <ToastRoot
            v-for="toast in toasts"
            :key="toast.id"
            :open="toast.open"
            :class="{
                'bg-green-200 text-green-800': toast.type === 'success',
                'bg-red-200 text-red-800': toast.type === 'error',
                'bg-yellow-100 text-yellow-800': toast.type === 'alert'
            }"
            :duration="toast.duration"
            class="py-6 px-4 rounded relative overflow-hidden toast-root"
            :style="`animation-duration: ${toast.duration}ms`"
        >
            <ToastTitle class="font-extrabold">{{ toast.title }}</ToastTitle>
            <ToastDescription>{{ toast.message }}</ToastDescription>
            <ToastAction as-child alt-text="Close toast" class="absolute p-1 rounded-full top-2 right-2 cursor-pointer hover:backdrop-brightness-90">
                <button @click="toast.open = false; removeToast(toast.id)">
                    <X :size="12"/>
                </button>
            </ToastAction>
        </ToastRoot>

        <ToastViewport class="fixed bottom-3 right-3 flex flex-col gap-2 min-w-[200px] max-w-full z-[2147483647]" />
    </ToastProvider>
</template>
<style scoped>
:deep(.toast-root)::before {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    height:0.3rem;
    background-color: inherit;
    animation: toastProgress linear forwards;
    animation-duration: inherit;
    filter: saturate(200%) brightness(40%);
}
@keyframes toastProgress {
    0% {
        width:0;
    }
    100% {
        width:100%;
    }
}
</style>
