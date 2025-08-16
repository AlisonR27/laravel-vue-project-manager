<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Table } from '@/components/ui/Table';

import { ref } from 'vue';

import TaskDialog from '@/components/TaskDialog.vue';


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

const props = defineProps({
    tasks: {
        type: Array<TasksRowKeys>,
        required: true,
    },
    deleted: {
        type: Boolean,
        required: false,
        default: false
    }
});

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

const openedTask = ref(0)
const isTaskOpen = ref(false)

function openTask({ id, type} : { id: number, type: string}) {

    switch (type) {
        case 'detail':
            openedTask.value = id
            isTaskOpen.value = true
            break
    }

}

const isAlertClosed = ref(false)

function closeTask() {
    openedTask.value = 0
    isTaskOpen.value = false
}
</script>

<template>
    <Head :title="`Tasks`" />
    <Suspense>
        <TaskDialog :is-open="isTaskOpen" :task-id="openedTask" @close="closeTask" />
    </Suspense>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template v-if="deleted && !isAlertClosed">
            <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded relative my-4" role="alert">
                <span class="block sm:inline">The task was deleted successfully.</span>
                <span class="absolute right-0 hover:bg-green-200 top-2 px-3 rounded-4xl py-1 cursor-pointer" role="button" @click="isAlertClosed = !isAlertClosed">
                        &times;
                    </span>
            </div>
        </template>
        <div class="flex flex-1 flex-col gap-4 rounded-xl px-4 py-2">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h3 class="text-xl font-bold text-accent-foreground">Filtros</h3>
                To do
            </div>
        </div>
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <Table title="All Tasks" :headers="headers" :rows="tasks" row-key="id" type="Tasks" :paginated="false" @task="openTask" />
        </div>
    </AppLayout>
</template>

<style scoped>
::v-deep(button[type="button"]:first-of-type){
    cursor:pointer !important;
}
</style>
