<script setup lang="ts">
import { DollarSign } from 'lucide-vue-next';
import { formatCurrency } from '@/lib/utils';
import { computed, onBeforeMount } from 'vue';

const props = defineProps(['value', 'label', 'icon', 'color']);

const formattedValue = computed(() => {
    if (Number.isInteger(props.value)) return props.value;
    else return formatCurrency(props.value, 'usd');
});

const cardVariants: Record<string, string[]> = {
    green: ['bg-green-200', 'hover:bg-green-300', 'dark:border-green-900'],
    indigo: ['bg-indigo-200', 'hover:bg-indigo-300', 'dark:border-indigo-900'],
    amber: ['bg-amber-200', 'hover:bg-amber-300', 'dark:border-amber-900'],
    red: ['bg-red-200', 'hover:bg-red-400', 'dark:border-red-950'],
    gray: ['bg-gray-200', 'hover:bg-gray-300', 'dark:border-gray-700'],
};

const iconVariants: Record<string, string[]> = {
    green: ['dark:bg-green-900'],
    indigo: ['dark:bg-indigo-900'],
    amber: ['dark:bg-amber-900'],
    red: ['dark:bg-red-950'],
    gray: ['dark:bg-gray-900'],
};

const cardClasses = computed(() => [...(cardVariants[props.color] ?? cardVariants.gray)]);

const iconClasses = computed(() => [...(iconVariants[props.color] ?? iconVariants.gray)]);
</script>

<template>
    <div
        :class="cardClasses"
        class="relative flex w-full flex-col items-center justify-center gap-2 overflow-hidden rounded-xl border border-sidebar-border/70 py-4 transition-all xl:aspect-video dark:bg-sidebar"
    >
        <div v-if="icon" class="aspect-square rounded-full p-2" :class="iconClasses">
            <Component :size="32" :is="icon" />
        </div>
        <b class="mt-2 text-3xl xl:text-4xl"> {{ formattedValue }} </b>
        <span> {{ label }} </span>
    </div>
</template>

<style scoped></style>
