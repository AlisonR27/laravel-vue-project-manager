<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const props = defineProps(['user_projects']);

console.log(props);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
                <h2 class="text-2xl font-bold text-accent-foreground">Recent Projects</h2>
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
        </div>
    </AppLayout>
</template>
