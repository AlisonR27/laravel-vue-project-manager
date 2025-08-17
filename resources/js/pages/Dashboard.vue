<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { DollarSign, Laptop, ClockAlert } from 'lucide-vue-next';

import { type BreadcrumbItem } from '@/types';

import { Head } from '@inertiajs/vue3';

import { computed } from 'vue';
import Badge from '@/components/ui/badge/Badge.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps(['user-projects', 'user-tasks', 'metrics'])

function formatCurrency(value: string) {
    let [int, dec] = value.split('.')
    int = int.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    return `${int}.${dec}`
}
const totalValueInProgress = computed(() => formatCurrency(props.metrics.totalValueInProgress))


</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap gap-10 overflow-x-auto rounded-xl p-4">
            <div class="grid h-min w-full auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative flex aspect-video flex-col items-center justify-center gap-2 overflow-hidden rounded-xl border border-sidebar-border/70 bg-green-200 transition-all hover:bg-green-300 dark:border-green-900 dark:bg-sidebar"
                >
                    <div class="aspect-square rounded-full p-2 dark:bg-green-900">
                        <DollarSign :size="32" />
                    </div>
                    <b class="mt-2 text-4xl"> $ {{ totalValueInProgress }} </b>
                    <span> Worth in projects </span>
                </div>
                <div
                    class="relative flex aspect-video flex-col items-center justify-center gap-2 overflow-hidden rounded-xl border border-sidebar-border/70 bg-indigo-200 transition-all hover:bg-indigo-300 dark:border-indigo-900 dark:bg-sidebar"
                >
                    <div class="aspect-square rounded-full p-2 dark:bg-indigo-900">
                        <Laptop :size="32" />
                    </div>
                    <b class="mt-2 text-4xl">
                        {{ metrics.totalProjects }}
                    </b>
                    <span> Projects </span>
                </div>
                <div
                    class="relative flex aspect-video flex-col items-center justify-center gap-2 overflow-hidden rounded-xl border border-sidebar-border/70 bg-red-100 transition-all hover:bg-red-400 dark:border-red-950 dark:bg-sidebar"
                >
                    <div class="aspect-square rounded-full p-2 dark:bg-red-950">
                        <ClockAlert :size="32" />
                    </div>
                    <b class="mt-2 text-4xl">
                        {{ metrics.delayed }}
                    </b>
                    <span> Late Projects </span>
                </div>
            </div>
            <div class="relative w-6/12 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-5 text-2xl font-bold text-accent-foreground">My Recent Projects</h2>
                <div role="list" class="flex flex-col gap-2">
                    <a
                        role="listitem"
                        :href="`/projects/${project.id}`"
                        v-for="project in userProjects"
                        :key="project.id"
                        class="flex flex-row items-center justify-between gap-5 rounded-sm border p-4 hover:bg-sidebar"
                    >
                        <span class="w-9/12">
                            {{ project.name }}
                        </span>
                        <Badge class="w-3/12" :status="project.status"/>
                    </a>
                </div>
            </div>
            <div class="relative w-5/12 flex-2 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-5 text-2xl font-bold text-accent-foreground">My Recent Tasks</h2>
                <div role="list" class="flex flex-col gap-2">
                    <a
                        role="listitem"
                        :href="`/tasks/${task.id}`"
                        v-for="task in userTasks"
                        :key="task.id"
                        class="flex flex-row items-center justify-between gap-5 rounded-sm border p-4 hover:bg-sidebar"
                    >
                        <span class="w-9/12">
                            {{ task.title }}
                        </span>
                        <Badge class="w-4/12" :status="task.status"/>
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
#metrics-value:deep(svg) {
    stroke: green;
}
td {
    text-align: start;
}
</style>
