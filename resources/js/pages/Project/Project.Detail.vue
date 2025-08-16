<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps(['project', 'tasks_bi']);

const projectInfoArray: Array<[string,any]> = Object.entries(props.project);

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
] as BreadcrumbItem[]

function handleValueFormatting(label: string, value: string) {
    if (label.includes("date")) {
        const date = new Date(value)
        return date.toLocaleDateString()
    } else {
        if (Number.isNaN(Number(value))) return value[0].toUpperCase() + value.substring(1)
        else if (!Number.isInteger(value)) {
            // eslint-disable-next-line prefer-const
            let [int, dec] = value.split('.')
            int = int.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            return `$ ${int}.${dec}`
        } else return Number(value)
    }
}


</script>

<template>
    <Head :title="`${project.name} - Projects`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <dl aria-description="Specific details of the project" class="info-list relative p-5 w-full overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h2> Project Details </h2> <br/>
                <div class="info-item" :key="key" v-for="[key, value] of projectInfoArray">
                    <dt :id="`label-${key}`" class="text-neutral-400"> {{ key.split('_').join(' ') }} </dt>
                    <dd v-if="key == 'creator'" :aria-labelledby="`label-${key}`"> {{ value.name }} </dd>
                    <dd v-else :aria-labelledby="`label-${key}`"> {{ handleValueFormatting(key, value) }} </dd>
                </div>
            </dl>
            <div class="w-full grid gap-2 grid-cols-3">
                <div :key="i" v-for="i of 3" class="relative p-5 w-full overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.info-item dt {
    text-transform: capitalize;
}
.info-list {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Duas colunas */
    gap: 0.5rem 2rem;
    margin: 0;
}

.info-list dt {
    font-weight: bold;
}

.info-list dd {
    margin: 0;
}

@media (max-width: 600px) {
    .info-list {
        grid-template-columns: 1fr; /* Empilha no mobile */
    }
}

</style>
