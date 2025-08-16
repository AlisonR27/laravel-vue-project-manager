<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Table } from '@/components/ui/table';
import DeleteItem from '@/components/DeleteItem.vue';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Projects',
        href: '#',
    },
] as BreadcrumbItem[]

type ProjectRowKeys = {
    id: number,
    name: string,
    status: string,
    updated_at: Date,
    creator: string
}

const props = defineProps({
    projects: {
        type: Array<ProjectRowKeys>,
        required: true
    },
    deleted: {
        type: Boolean,
        required: false
    }
})

const headers = [
    {
        key: 'id',
        label: '#'
    },
    {
        key: 'name',
        label: 'Name',
        colspan: 2
    },
    {
        key: 'status',
        label: 'Status'
    },
    {
        key: 'creator',
        label: 'Created By'
    },
    {
        key: 'actions',
        label: ''
    }
]

const deleteOpen = ref(false)

const deleteModalId = ref(0)

function handleProjectAction({ id, type} : { id: number, type: string}) {
    switch(type) {
        case "detail":
            router.visit(`/projects/${id}`)
            break
        case "edit":
            router.visit(`/projects/${id}/edit`)
            break
        case "delete":
            deleteOpen.value = true
            deleteModalId.value = id
            break
    }
}
</script>

<template>
    <Head :title="`Projects`" />

    <DeleteItem type="project" :item-id="deleteModalId" :open="deleteOpen" @close="deleteOpen = false" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 rounded-xl py-2 px-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h3  class="text-xl font-bold text-accent-foreground"> Filtros </h3>
                To do
            </div>
        </div>
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <Table title="Recent Projects" :headers="headers" :rows="projects" row-key="id" :paginated="false" type="Projects" @project="handleProjectAction"/>
        </div>
    </AppLayout>
</template>

<style scoped></style>
