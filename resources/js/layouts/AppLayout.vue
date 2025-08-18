<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Toast } from '@/components/ui/toast';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Toast />
    <AppLayout :breadcrumbs="breadcrumbs" class="dark:scheme-dark">
        <slot />
    </AppLayout>
</template>
