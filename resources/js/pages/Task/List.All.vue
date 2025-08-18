<script setup lang="ts">
import { computed, onBeforeMount, ref, watch, watchEffect } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';

import { Table } from '@/components/ui/Table';

import AppLayout from '@/layouts/AppLayout.vue';
import TaskDialog from '@/components/TaskDialog.vue';
import TaskSearchForm from '@/components/TaskSearchForm.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import initializeFilters from '@/composables/useFilterInitializer';

import { useTasksStore } from '@/store/tasks';

import { addToast } from '@/composables/useToast';

const taskStore = useTasksStore();

const breadcrumbs = [
    {
        title: 'Home',
        href: '/',
    },
    {
        title: 'Tasks',
        href: '#',
    },
] as BreadcrumbItem[];

type TasksRowKeys = {
    id: number;
    title: string;
    status: string;
    updated_at: Date;
    project: string;
};

const props = defineProps(['tasks', 'alert']);

const headers = [
    {
        key: 'id',
        label: '#',
    },
    {
        key: 'title',
        label: 'Title',
        colspan: 2,
    },
    {
        key: 'status',
        label: 'Status',
    },
    {
        key: 'project',
        label: 'Project',
    },
    {
        key: 'actions',
        label: '',
    },
];

const openedTask = ref(0);
const isTaskOpen = ref(false);

const pageSize = computed(() => {
    return useTasksStore().filters.page_size;
});
const page = computed(() => {
    return useTasksStore().filters.page;
});

function openTask({ id, type }: { id: number; type: string }) {
    switch (type) {
        case 'detail':
            openedTask.value = id;
            isTaskOpen.value = true;
            break;
    }
}

function closeTask() {
    openedTask.value = 0;
    isTaskOpen.value = false;
}
function handleFormFilters(filters: any) {
    router.get(route('task.all'), filters);
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

onBeforeMount(() => {
    initializeFilters(taskStore);
});

watch(
    () => props.alert,
    (newAlert) => { if (newAlert != null) addToast('Alert', newAlert!.message, newAlert!.type) },
    { immediate: true }
)
</script>

<template>
    <Head :title="`Tasks`" />
    <Suspense>
        <TaskDialog :is-open="isTaskOpen" :task-id="openedTask" @close="closeTask" />
    </Suspense>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 rounded-xl px-4 py-2">
            <TaskSearchForm @filter="handleFormFilters" />
        </div>
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <Table title="All Tasks" :headers="headers" :rows="tasks.data" row-key="id" type="Tasks" :paginated="false" @task="openTask">
                    <template v-slot:empty>
                        <td colspan="5" class="py-10">
                            <b class="w-full text-center text-xl"> No Tasks found on the registry </b>
                        </td>
                    </template>
                </Table>
                <Pagination
                    v-if="tasks.total > 8"
                    @paginate="handlePaginate"
                    @size="handlePageSize"
                    class="mt-5 flex flex-row"
                    :total="tasks.total"
                    :currentPage="Number(page)"
                    :page_size="Number(pageSize)"
                />
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
::v-deep(button[type='button']:first-of-type) {
    cursor: pointer !important;
}
</style>
