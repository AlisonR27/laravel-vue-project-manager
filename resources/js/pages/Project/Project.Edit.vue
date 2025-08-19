<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import { ref } from 'vue';

const props = defineProps(['project', 'updated'])

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Projects',
        href: '/projects',
    },
    {
        title: 'Edit',
        href: '#'
    }
] as BreadcrumbItem[]

const form = useForm({
    name: props.project.name || '',
    start_date: props.project.start_date || '',
    end_date: props.project.end_date || '',
    value: props.project.value || '',
    status: props.project.status || 'Active',
})

const submit = () => {
    if (props.project.status == "Active" && form.status == "Inactive") {
        if(window.confirm('Are you sure?\nAll child Tasks will be turned Inactive.\nThis action is irreversible')) {
            form.put(route('project.update', props.project.id))
        }
    } else {
        form.put(route('project.update', props.project.id))
    }
}

const isAlertClosed = ref(false)
</script>

<template>
    <Head :title="`${project.name} - Editing Project`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="gap-4 rounded-xl p-4">
            <template v-if="updated && !isAlertClosed">
                <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded relative my-4" role="alert">
                    <span class="block sm:inline">The project was updated successfully</span>
                    <span class="absolute right-0 hover:bg-green-200 top-2 px-3 rounded-4xl py-1 cursor-pointer" role="button" @click="isAlertClosed = !isAlertClosed">
                        &times;
                    </span>
                </div>
            </template>
            <h1 class="text-2xl font-bold mb-4">Editar Projeto</h1>
            <form @submit.prevent="submit" class="gap-4 grid grid-cols-3">
                <!-- Nome -->
                <div class="col-span-3">
                    <label for="name" class="block font-medium">Name</label>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        maxlength="60"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                </div>

                <!-- Data início -->
                <div>
                    <label for="start_date" class="block font-medium">Start Date</label>
                    <input
                        id="start_date"
                        type="date"
                        v-model="form.start_date"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.start_date" class="text-red-600 text-sm">{{ form.errors.start_date }}</div>
                </div>

                <!-- Data fim -->
                <div>
                    <label for="end_date" class="block font-medium">End Date</label>
                    <input
                        id="end_date"
                        type="date"
                        v-model="form.end_date"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.end_date" class="text-red-600 text-sm">{{ form.errors.end_date }}</div>
                </div>

                <!-- Valor -->
                <div class="">
                    <label for="value" class="block font-medium">Value</label>
                    <input
                        id="value"
                        type="number"
                        step="0.01"
                        min="0"
                        v-model="form.value"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.value" class="text-red-600 text-sm">{{ form.errors.value }}</div>
                </div>

                <!-- Status -->
                <div class="col-span-3">
                    <label for="status" class="block font-medium">Status</label>
                    <select
                        id="status"
                        v-model="form.status"
                        class="w-full border rounded p-2"
                    >
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-600 text-sm">{{ form.errors.status }}</div>
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
