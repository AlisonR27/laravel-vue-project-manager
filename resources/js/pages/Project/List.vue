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
const projectsStore = useProjectStore()

const deleteOpen = ref(false);

const deleteModalId = ref(0);

const pageSize = computed(() => {
    return projectsStore.filters.page_size;
});
const page = computed(() => {
    return projectsStore.filters.page;
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
    const cleanFilters = projectsStore.getOnlyFilled()
    if(cleanFilters.order_by) {
        const orderString = projectsStore.filters.order_by as any
        const [order_by, order] = orderString.split('-')
        delete cleanFilters.order_by
        Object.assign(cleanFilters,{order_by, order})
    }
    delete cleanFilters.page
    delete cleanFilters.page_size
    router.get(route('project.all'), cleanFilters);
}

function handlePaginate(page: any) {
    router.get(route('project.all'), { ...useTasksStore().getOnlyFilled(), page }, { preserveState: true });
}

/**
 * After selecting a page size, if it's other than default put it to the query, if is the default, remove from the query
 * Obs. Replicate to each list page
 * @param page_size
 */
function handlePageSize(page_size: number) {
    const filled = useTasksStore().getOnlyFilled();
    if (page_size != 10) router.get(route('project.all'), { ...filled, page_size }, {});
    else {
        delete filled.page_size;
        router.get(route('project.all'), { ...filled }, {});
    }
}

function handleOrderBy() {
    const filled = useTasksStore().getOnlyFilled();
    const orderString = projectsStore.filters.order_by as any
    delete filled.page
    delete filled.page_size
    const [order_by, order] = orderString.split('-')
    router.get(route('project.all'), {...filled, order_by, order})
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
                <div class="absolute top-5 right-5">
                    <label for="order_by" class="mr-2">Order:</label>
                    <select v-model="projectsStore.filters.order_by" name="order_by" id="order_by" class="border rounded py-2" @change="handleOrderBy">
                        <option :value="null" selected> Default </option>
                        <option value="name-asc">Name (asc)</option>
                        <option value="name-desc">Name (desc)</option>
                        <option value="updated_at-asc">Last Updated (asc) </option>
                        <option value="updated_at-desc">Last Updated (desc) </option>
                        <option value="id-asc">Identifier (asc)</option>
                        <option value="id-desc">Identifier (desc)</option>
                    </select>
                </div>
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
