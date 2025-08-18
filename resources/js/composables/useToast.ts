import { ref } from 'vue'

export type ToastType = 'success' | 'error' | 'alert'

export interface ToastItem {
    id: number
    title: string
    message: string
    type: ToastType,
    open: any,
    duration?: number
}

const toasts = ref<ToastItem[]>([])
let counter = 0


export function addToast(title: string, message: string, type: 'success' | 'error' | 'alert', duration = 3000) {
    const id = Date.now(); // ou algum UUID
    toasts.value.push({ id, title, message, type, open: ref(true), duration });

    setTimeout(() => {
        toasts.value = toasts.value.filter(t => t.id !== id);
    }, duration);
}



export function removeToast(id: number) {
    toasts.value = toasts.value.filter(t => t.id !== id)
}

export function useToastProvider() {
    return { toasts, removeToast }
}
