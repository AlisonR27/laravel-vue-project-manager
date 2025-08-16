<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { DropdownMenuContent, DropdownMenuItem, DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuSeparator, DropdownMenuArrow } from 'reka-ui';
import { EllipsisVertical, Edit, Trash } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

import DeleteItem from '@/components/DeleteItem.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';


import { ref } from 'vue';

const props = defineProps(['task']);

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
        <DropdownMenuRoot class="absolute right-0">
            <DropdownMenuTrigger as-child>
                <Button class="absolute top-3 right-0 cursor-pointer" variant="ghost"> <EllipsisVertical /> </Button>
            </DropdownMenuTrigger>

            <DropdownMenuContent side="bottom" align="end" class="rounded-md p-2">
                <DropdownMenuItem
                    class="DropDownMenuItem rounded-md"
                    role="menuitem"
                    @select.prevent="router.visit(`/task/${props.task.id}/edit`)"
                >
                    <Edit :size="16" /> Edit
                </DropdownMenuItem>


                <DropdownMenuSeparator />

                <DropdownMenuItem
                    class="DropDownMenuItem Destructive rounded-md"
                    role="menuitem"
                    @select.prevent.stop="openDelete"
                >
                    <Trash :size="16" /> Delete
                </DropdownMenuItem>

                <DropdownMenuArrow />
            </DropdownMenuContent>
        </DropdownMenuRoot>

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
[data-reka-popper-content-wrapper] {
    background: var(--sidebar-background);
    filter: drop-shadow(0 0 1px  white);
    z-index: 10 !important;
    border-radius:0.25rem;
}

[data-reka-popper-content-wrapper]:deep(div:only-child) {
}

[data-reka-popper-content-wrapper]:deep(span, span *) {
    border-color: var(--sidebar-background) ;
    fill: var(--sidebar-background) ;
}
[data-reka-popper-content-wrapper]:deep(span) {
    left: unset;
    right: 15px !important;
}
.DropDownMenuItem {
    padding-block: calc(var(--spacing) * 3);
    padding-inline: calc(var(--spacing) * 6);
    display: flex;
    flex-direction: row;
    justify-content: start;
    cursor: pointer;
    align-items: center;
    gap: 0.8rem;
}
.DropDownMenuItem:hover {
    background-color: var(--sidebar-accent);
}
.DropDownMenuItem.Destructive:hover {
    background: var(--destructive);
}
</style>
