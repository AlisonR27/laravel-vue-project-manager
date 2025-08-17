<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { DollarSign, Laptop, ClockAlert } from 'lucide-vue-next';

import { type BreadcrumbItem } from '@/types';

import { Head } from '@inertiajs/vue3';

import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps(['user_projects', 'metrics'])

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
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="transition-all dark:bg-sidebar relative aspect-video overflow-hidden rounded-xl border dark:border-green-900 border-sidebar-border/70 flex flex-col gap-2 items-center justify-center">
                    <div class="p-2 rounded-full aspect-square dark:bg-green-900">
                        <DollarSign :size="32" />
                    </div>
                    <b class="text-4xl mt-2">
                        $ {{ totalValueInProgress }}
                    </b>
                    <span>
                        Worth in projects
                    </span>
                </div>
                 <div class="transition-all dark:bg-sidebar relative aspect-video overflow-hidden rounded-xl border dark:border-indigo-900 border-sidebar-border/70 flex flex-col gap-2 items-center justify-center">
                    <div class="p-2 rounded-full aspect-square dark:bg-indigo-900">
                        <Laptop :size="32" />
                    </div>
                    <b class="text-4xl mt-2">
                        {{ metrics.totalProjects }}
                    </b>
                    <span>
                        Projects
                    </span>
                </div>
                <div class="transition-all dark:bg-sidebar relative aspect-video overflow-hidden rounded-xl border dark:border-red-950 border-sidebar-border/70 flex flex-col gap-2 items-center justify-center">
                    <div class="p-2 rounded-full aspect-square dark:bg-red-950">
                        <ClockAlert :size="32" />
                    </div>
                    <b class="text-4xl mt-2">
                        {{ metrics.delayed }}
                    </b>
                    <span>
                        Late Projects
                    </span>
                </div>
<!--                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border dark:bg-sidebar flex flex-col items-center justify-center">-->
<!--                    <span class="sr-only"> Quantitative data</span>-->
<!--                    <b class="text-7xl">-->
<!--                        {{ metrics.totalProjects }}-->
<!--                    </b>-->
<!--                    <span class="mt-2">-->
<!--                        Projects-->
<!--                    </span>-->
<!--                </div>-->
<!--                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col items-center justify-center">-->
<!--                    <b class="text-7xl">-->
<!--                        {{ metrics.inProgress }}-->
<!--                    </b>-->
<!--                    <span class="mt-2">-->
<!--                        In progress-->
<!--                    </span>-->
<!--                    <PlaceholderPattern />-->
<!--                </div>-->
            </div>
            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="text-2xl font-bold text-accent-foreground">My Recent Projects</h2>
                <table class="w-full table-auto border-collapse border mt-5">
                    <thead>
                        <tr>
                            <th class="border">#</th>
                            <th class="border" colspan="1">Name</th>
                            <th class="border">Status</th>
                            <th class="border">End-Date</th>
                            <th class="border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :key="project.id" v-for="project in user_projects" class="py-5 border table-border">
                            <td  class="border px-4 py-3">
                                {{ project.id }}
                            </td>
                            <td  class="border px-4 py-3">
                                <a :href="`/projects/${project.id}`">
                                    {{ project.name }}
                                </a>
                            </td>
                            <td class="border px-4 py-3">
                                {{ project.status }}
                            </td>
                            <td class="border px-4 py-3">
                                {{ project.end_date }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="relative flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="text-2xl font-bold text-accent-foreground">My Recent Tasks</h2>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
#metrics-value:deep(svg) {
    stroke: green;
}
#metrics > span, #metrics > b {
}
</style>
