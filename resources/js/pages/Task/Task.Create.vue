<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button';

import { ref } from 'vue';

const props = defineProps(['projects'])

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
    title: '',
    due_date: new Date().toISOString().split('T')[0],
    project_id: ''
})

const submit = () => {
    form.post(route('task.create'))
}

</script>

<template>
    <Head :title="`Creating a new task`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold mb-4">Create Task</h1>
            <form @submit.prevent="submit" class="gap-4 grid grid-cols-3">
                <!-- Nome -->
                <div class="col-span-2">
                    <label for="name" class="block font-medium">Title</label>
                    <input
                        required
                        id="name"
                        type="text"
                        v-model="form.title"
                        maxlength="60"
                        class="w-full border rounded p-2"
                        placeholder="My task title"
                    />
                    <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
                </div>

                <!-- Data início -->
                <div>
                    <label for="due_date" class="block font-medium">Due date</label>
                    <input
                        required
                        id="due_date"
                        type="date"
                        v-model="form.due_date"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.due_date" class="text-red-600 text-sm">{{ form.errors.due_date }}</div>
                </div>
                <div class="w-full">
                    <label for="project_id" class="block font-medium">Project</label>
                    <select required id="project_id" class="w-full border rounded p-2" v-model="form.project_id">
                        <option value="" disabled selected>Choose a project</option>
                        <option v-for="project in projects" :key="project.id" :value="project.id">
                            {{ project.name }}
                        </option>
                    </select>
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
