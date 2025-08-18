<script setup lang="ts">
import { useToastProvider } from '@/composables/useToast'
import { ToastRoot, ToastTitle, ToastDescription, ToastAction, ToastProvider, ToastViewport } from 'reka-ui'
import { X } from 'lucide-vue-next'

const { toasts, removeToast } = useToastProvider()
</script>

<template>
    <ToastProvider>
        <ToastRoot
            v-for="toast in toasts"
            :key="toast.id"
            v-model:open="toast.open"
            :class="{
                'bg-green-200 text-green-800': toast.type === 'success',
                'bg-red-200 text-red-800': toast.type === 'error',
                'bg-yellow-100 text-yellow-800': toast.type === 'alert'
            }"
            class="p-4 rounded relative"
        >
            <ToastTitle class="font-extrabold">{{ toast.title }}</ToastTitle>
            <ToastDescription>{{ toast.message }}</ToastDescription>
            <ToastAction as-child alt-text="Close toast" class="absolute p-1 rounded-full top-2 right-2 cursor-pointer hover:backdrop-brightness-90">
                <button @click="toast.open = false; removeToast(toast.id)">
                    <X :size="12"/>
                </button>
            </ToastAction>
        </ToastRoot>

        <ToastViewport class="fixed bottom-3 right-3 flex flex-col gap-2 w-[390px] max-w-full z-[2147483647]" />
    </ToastProvider>
</template>
