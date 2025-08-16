<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button';

import { ref } from 'vue';

const props = defineProps(['task', 'updated'])

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tasks',
        href: '/tasks',
    },
    {
        title: 'Edit',
        href: '#'
    }
] as BreadcrumbItem[]

const form = useForm({
    title: props.task.title || '',
    due_date: props.task.due_date || '',
})

const submit = () => {
    form.put(route('task.update', props.task.id))
}

const isAlertClosed = ref(false)
</script>

<template>
    <Head :title="`${task.title} - Editing Task`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="gap-4 rounded-xl p-4">
            <template v-if="updated && !isAlertClosed">
                <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded relative my-4" role="alert">
                    <span class="block sm:inline">The task was updated successfully</span>
                    <span class="absolute right-0 hover:bg-green-200 top-2 px-3 rounded-4xl py-1 cursor-pointer" role="button" @click="isAlertClosed = !isAlertClosed">
                        &times;
                    </span>
                </div>
            </template>
            <h1 class="text-2xl font-bold mb-4">Edit Task</h1>
            <form @submit.prevent="submit" class="gap-4 grid grid-cols-3">
                <!-- Nome -->
                <div class="col-span-3">
                    <label for="name" class="block font-medium">Nome</label>
                    <input
                        id="name"
                        type="text"
                        v-model="form.title"
                        maxlength="60"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
                </div>

                <!-- Data início -->
                <div>
                    <label for="start_date" class="block font-medium">Data de Início</label>
                    <input
                        id="start_date"
                        type="date"
                        v-model="form.due_date"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.due_date" class="text-red-600 text-sm">{{ form.errors.due_date }}</div>
                </div>
                <!-- Botão -->
                <Button
                    variant="default"
                    type="submit"
                    :disabled="form.processing"
                    class="cursor-pointer col-start-1"
                >
                    Salvar
                </Button>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped></style>
