<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Table } from '@/components/ui/table';
import DeleteItem from '@/components/DeleteItem.vue';
import { computed, onMounted, ref, watch } from 'vue';
import ProjectSearchForm from '@/components/ProjectSearchForm.vue';

import { addToast } from '@/composables/useToast';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { useTasksStore } from '@/store/tasks';
import { useProjectStore } from '@/store/projects';

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
        type: Object,
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

const pageSize = computed(() => {
    return useProjectStore().filters.page_size;
});
const page = computed(() => {
    return useProjectStore().filters.page;
});

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
    const cleanFilters = filters
    delete cleanFilters.page
    delete cleanFilters.page_size
    router.get(route('project.all'), cleanFilters);
}

function handlePaginate(page: any) {
    router.get(route('task.all'), { ...useTasksStore().getOnlyFilled(), page }, { preserveState: true });
}

/**
 * After selecting a page size, if it's other than default put it to the query, if is the default, remove from the query
 * Obs. Replicate to each list page
 * @param page_size
 */
function handlePageSize(page_size: number) {
    const filled = useTasksStore().getOnlyFilled();
    if (page_size != 10) router.get(route('task.all'), { ...filled, page_size }, {});
    else {
        delete filled.page_size;
        router.get(route('task.all'), { ...filled }, {});
    }
}

watch(
    () => props.alert,
    (newAlert) => {
        if (newAlert != null) addToast('Alert', newAlert!.message, newAlert!.type);
    },
    { immediate: true },
);
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
                    :rows="projects.data"
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
                <Pagination
                    v-if="projects.total > 8"
                    @paginate="handlePaginate"
                    @size="handlePageSize"
                    class="mt-5 flex flex-row"
                    :total="projects.total"
                    :currentPage="Number(page)"
                    :page_size="Number(pageSize)"
                />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
