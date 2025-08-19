<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { EllipsisVertical, Edit, Trash } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

import DeleteItem from '@/components/DeleteItem.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';


import { ref } from 'vue';
import DetailDropdown from '@/components/DetailDropdown.vue';

const props = defineProps(['task', 'can']);

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
        title: props.task.title,
        href: '#',
    },
] as BreadcrumbItem[];

const deleteOpen = ref(false)

function openDelete() {
    deleteOpen.value = true
}
</script>

<template>
    <Head :title="`${task.title} - Tasks`" />

    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <DetailDropdown
            type="task"
            :can="can"
            :id="task.id"
            @delete="openDelete"
        />

        <DeleteItem type="task" :item-id="task.id" :open="deleteOpen" @close="deleteOpen = false" />


        <dl
            aria-description="Specific details of the project"
            class="info-list relative grid w-full grid-cols-2 gap-4 overflow-hidden rounded-xl p-5"
        >
            <div class="info-item">
                <dt class="font-medium">ID:</dt>
                <span class="block">{{ task.id }}</span>
            </div>
            <div class="info-item">
                <dt class="font-medium">Title:</dt>
                <span class="block">{{ task.title }}</span>
            </div>
            <div class="info-item">
                <dt class="font-medium">Status:</dt>
                <dd class="block">{{ task.status }}</dd>
            </div>
            <div class="info-item">
                <dt class="font-medium">Created at:</dt>
                <dd class="block">{{ new Date(task.created_at).toLocaleString() }}</dd>
            </div>
            <div class="info-item">
                <dt class="font-medium">Due Date:</dt>
                <dd class="block">{{ new Date(task.due_date).toLocaleString() }}</dd>
            </div>
            <div class="info-item">
                <dt class="font-medium">Last Updated:</dt>
                <dd class="block">{{ new Date(task.updated_at).toLocaleString() }}</dd>
            </div>

            <!-- Informações do Projeto -->
            <div>
                <dt class="font-medium text-gray-500">Projeto:</dt>
                <dd class="block">{{ task.project?.name }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Status do Projeto:</dt>
                <dd class="block">{{ task.project?.status }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Valor do Projeto:</dt>
                <dd class="block">{{ task.project?.value }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Início do Projeto:</dt>
                <dd class="block">{{ task.project?.start_date }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Término do Projeto:</dt>
                <dd class="block">{{ task.project?.end_date }}</dd>
            </div>
        </dl>
    </AppLayout>
</template>

<style scoped>
dt {
    font-weight: bold;
    color: var(--color-gray-400);
}

</style>
