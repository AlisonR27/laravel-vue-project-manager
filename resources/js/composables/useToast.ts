import { ref } from 'vue'

export type ToastType = 'success' | 'error' | 'alert'

export interface ToastItem {
    id: number
    title: string
    message: string
    type: ToastType,
    open: any
}

const toasts = ref<ToastItem[]>([])
let counter = 0

export function addToast(title: string, message: string, type: ToastType = 'alert') {
    const id = counter++
    toasts.value.push({
        id,
        title,
        message,
        type,
        open: ref(true)
    })
    return id
}


export function removeToast(id: number) {
    toasts.value = toasts.value.filter(t => t.id !== id)
}

export function useToastProvider() {
    return { toasts, removeToast }
}
