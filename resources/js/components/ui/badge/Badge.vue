<script setup lang="ts">
    import { computed } from 'vue';

    const props = defineProps(['status', 'icon'])

    const statusClasses: Record<string, string[]> = {
        Completed: [
            'border-green-600',
            'text-green-600',
            'hover:bg-green-600',
            'dark:bg-green-700'
        ],
        Active: [
            'border-blue-600',
            'text-blue-600',
            'hover:bg-blue-600',
            'dark:bg-blue-950'
        ],
        Inactive: [
            'border-red-600',
            'text-red-600',
            'hover:bg-red-600',
            'dark:bg-red-950'
        ],
        Pending: [
            'border-amber-500',
            'text-amber-500',
            'hover:bg-amber-500',
            'dark:bg-amber-900'
        ],
        Default: [
            'border-gray-500',
            'text-gray-500',
            'hover:bg-gray-500',
            'dark:bg-gray-700'
        ]
    }

    const classes = computed(() => {
        const base = ['badge', 'rounded-sm', 'border', 'hover:text-white', 'dark:text-white', '']
        return [...base, ...(statusClasses[props.status] ?? statusClasses.Default)]
    })
</script>

<template>
    <div :class="classes">
        <component v-if="icon" :size="16" :is="icon" class="flex md:hidden"/>
        <span class="hidden md:inline">{{ status }}</span>
    </div>
</template>

<style scoped>
.badge {
    padding: 4px 10px;
    box-sizing: content-box;
    text-align: center;
}
</style>
