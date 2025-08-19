<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { ChevronRight, Edit, EllipsisVertical, Trash } from 'lucide-vue-next';
import MetricCard from '@/components/MetricCard.vue';
import { Table } from '@/components/ui/table';
import DetailDropdown from '@/components/DetailDropdown.vue';
import DeleteItem from '@/components/DeleteItem.vue';
import { ref } from 'vue';

const props = defineProps(['project', 'tasks', 'tasks_bi', 'can']);

const projectInfoArray: Array<[string, any]> = Object.entries(props.project);

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
        title: props.project.name,
        href: '#',
    },
] as BreadcrumbItem[];

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
        key: 'actions',
        label: '',
    },
];

function handleValueFormatting(label: string, value: string) {
    if (label.includes('_date')) {
        const date = new Date(value);
        return date.toLocaleDateString();
    } else if (label.includes('_at')) {
        return new Date(value).toLocaleString();
    } else {
        if (Number.isNaN(Number(value))) return value[0].toUpperCase() + value.substring(1);
        else if (!Number.isInteger(value)) {
            // eslint-disable-next-line prefer-const
            let [int, dec] = value.split('.');
            int = int.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return `$ ${int}.${dec}`;
        } else return Number(value);
    }
}

function redirectToTask(id: number) {
    router.visit('/tasks/'+ id)
}

const deleteOpen = ref(false)

function handleDelete() {
    deleteOpen.value = true
}
</script>

<template>
    <Head :title="`${project.name} - Projects`" />

    <DeleteItem type="project" :item-id="project.id" :open="deleteOpen" @close="deleteOpen = false" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <DetailDropdown type="projects" :can="can" :id="project.id" @delete="handleDelete" />
            <div class="w-full rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border">
                <h2 class="text-2xl font-black">{{ project.name }}</h2>
                <sub class="mt-2 block text-zinc-700 dark:text-zinc-400">
                    Last updated at: {{ handleValueFormatting('updated_at', project.updated_at) }}
                </sub>
                <br />
                <dl
                    aria-description="Specific details of the project"
                    class="relative flex w-9/12 flex-row flex-wrap gap-6 overflow-hidden text-lg xl:w-6/12"
                >
                    <div class="info-item w-4/12 lg:w-2/12">
                        <dt id="label-id" class="text-neutral-400">Identifier</dt>
                        <dd aria-labelledby="label-id">{{ project.id }}</dd>
                    </div>

                    <div class="info-item lg:w-4/12">
                        <dt id="label-value" class="text-neutral-400">Value</dt>
                        <dd aria-labelledby="label-value">{{ handleValueFormatting('value', project.value) }}</dd>
                    </div>

                    <div class="info-item lg:w-4/12">
                        <dt id="label-status" class="text-neutral-400">Status</dt>
                        <dd aria-labelledby="label-status">{{ handleValueFormatting('status', project.status) }}</dd>
                    </div>

                    <div class="info-item lg:w-3/12">
                        <dt id="label-creator" class="text-neutral-400">Creator</dt>
                        <dd aria-labelledby="label-creator">{{ project.creator.name }}</dd>
                    </div>

                    <div class="info-item">
                        <dt id="label-start" class="text-neutral-400">Start Date</dt>
                        <dd aria-labelledby="label-start">{{ handleValueFormatting('start_date', project.start_date) }}</dd>
                    </div>

                    <div class="info-item">
                        <dt id="label-end" class="text-neutral-400">End Date</dt>
                        <dd aria-labelledby="label-end">{{ handleValueFormatting('end_date', project.end_date) }}</dd>
                    </div>

                    <div class="info-item">
                        <dt id="label-created" class="text-neutral-400">Created At</dt>
                        <dd aria-labelledby="label-created">{{ handleValueFormatting('created_at', project.created_at) }}</dd>
                    </div>
                </dl>
                <div class="mt-10 hidden w-full flex-row gap-4 md:flex">
                    <MetricCard
                        v-for="[key, value] of Object.entries(tasks_bi)"
                        :key="key"
                        color="gray"
                        :label="'tasks ' + key"
                        :value="value"
                        class="aspect-square !w-2/12 px-5"
                    />
                </div>
            </div>
            <div class="relative w-full rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border">
                <a
                    :href="`/tasks/?project_id=${project.id}`"
                    class="absolute top-5 right-5 flex flex-row items-center gap-2 rounded px-2 py-1 hover:bg-accent"
                >
                    See all
                    <ChevronRight :size="16" class="" />
                </a>
                <Table
                    title="Recent tasks of this project"
                    :headers="headers"
                    :rows="tasks"
                    row-key="id"
                    :paginated="false"
                    type="Tasks"
                    @task="redirectToTask"
                >
                    <template v-slot:empty>
                        <td colspan="5" class="py-10">
                            <b class="w-full text-center text-xl"> No recent tasks found </b>
                        </td>
                    </template>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.info-item dt {
    text-transform: capitalize;
}
.info-list dt {
    font-weight: bold;
}

.info-item {
}

:deep(div b ~ span) {
    text-transform: capitalize;
}
</style>
