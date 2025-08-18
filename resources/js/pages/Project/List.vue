<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Table } from '@/components/ui/table';
import DeleteItem from '@/components/DeleteItem.vue';
import { onMounted, ref, watch } from 'vue';
import ProjectSearchForm from '@/components/ProjectSearchForm.vue';

import { addToast } from '@/composables/useToast';

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Projects',
        href: '#',
    },
] as BreadcrumbItem[];

type ProjectRowKeys = {
    id: number;
    name: string;
    status: string;
    updated_at: Date;
    creator: string;
};

const props = defineProps({
    projects: {
        type: Array<ProjectRowKeys>,
        required: true,
    },
    alert: {
        type: Object,
        required: false,
    },
});

const headers = [
    {
        key: 'id',
        label: '#',
    },
    {
        key: 'name',
        label: 'Name',
        colspan: 2,
    },
    {
        key: 'status',
        label: 'Status',
    },
    {
        key: 'creator',
        label: 'Created By',
    },
    {
        key: 'actions',
        label: '',
    },
];

const deleteOpen = ref(false);

const deleteModalId = ref(0);

function handleProjectAction({ id, type }: { id: number; type: string }) {
    switch (type) {
        case 'detail':
            router.visit(`/projects/${id}`);
            break;
        case 'edit':
            router.visit(`/projects/${id}/edit`);
            break;
        case 'delete':
            deleteOpen.value = true;
            deleteModalId.value = id;
            break;
    }
}

function handleFormFilters(filters: any) {
    router.get(route('project.all'), filters);
}

watch(
    () => props.alert,
    (newAlert) => {
        addToast('Alert', newAlert!.message, newAlert!.type);
})
</script>

<template>
    <Head :title="`Projects`" />

    <DeleteItem type="project" :item-id="deleteModalId" :open="deleteOpen" @close="deleteOpen = false" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 rounded-xl px-4 py-2">
            <ProjectSearchForm @filter="handleFormFilters" />
        </div>
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <Table
                    title="Recent Projects"
                    :headers="headers"
                    :rows="projects"
                    row-key="id"
                    :paginated="false"
                    type="Projects"
                    @project="handleProjectAction"
                >
                    <template v-slot:empty>
                        <td colspan="5" class="py-10">
                            <b class="w-full text-center text-xl"> No projects found on the registry </b>
                        </td>
                    </template>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
