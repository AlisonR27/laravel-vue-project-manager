<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { DollarSign, Laptop, ClockAlert, CirclePlay, ChevronsRight, Ban, CircleCheck, CircleDotDashed } from 'lucide-vue-next';

import { type BreadcrumbItem } from '@/types';

import { Head } from '@inertiajs/vue3';

import { computed } from 'vue';
import Badge from '@/components/ui/badge/Badge.vue';
import MetricCard from '@/components/MetricCard.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps(['user-projects', 'user-tasks', 'metrics'])

const badgeIcon = (status: string) => {
    switch(status){
        case "Inactive":
            return Ban
        case "Active":
            return ChevronsRight
        case "Pending":
            return CircleDotDashed
        case "Completed":
            return CircleCheck
    }
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap gap-10 overflow-x-auto rounded-xl p-4">
            <div class="grid h-min w-full gap-4 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 overflow-x-hidden ">
                <MetricCard key="total-value" color="green" :icon="DollarSign" label="Worth in projects" :value="metrics.totalValueInProgress"/>
                <MetricCard key="local-value" color="gray" :icon="CirclePlay" label="Active Projects" :value="metrics.inProgress"/>
                <MetricCard key="late-projects" color="red" :icon="ClockAlert" label="Late Projects" :value="metrics.delayed"/>
                <MetricCard key="total-projects" color="indigo" :icon="Laptop" label="Total projects" :value="metrics.totalProjects" class="lg:hidden xl:flex"/>
            </div>
            <!-- Lists -->
            <div class="relative lg:w-6/12 flex-2 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
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
                        <Badge class="md:w-3/12" :status="project.status" :icon="badgeIcon(project.status)"/>
                    </a>
                </div>
            </div>
            <div class="relative lg:w-5/12 flex-2 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="mb-5 text-2xl font-bold text-accent-foreground">My Recent Tasks</h2>
                <div role="list" class="flex flex-col gap-2">
                    <a
                        role="listitem"
                        :href="`/tasks/${task.id}`"
                        v-for="task in userTasks"
                        :key="task.id"
                        class="flex flex-row items-center justify-between gap-5 rounded-sm border p-4 hover:bg-sidebar"
                    >
                        <span class="w-9/12 overflow-x-hidden whitespace-nowrap text-ellipsis">
                            {{ task.title }}
                        </span>
                        <Badge class="md:w-3/12" :status="task.status" :icon="badgeIcon(task.status)"/>
                    </a>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
td {
    text-align: start;
}
</style>
