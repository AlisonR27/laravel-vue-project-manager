<script setup lang="ts">
    import { computed } from 'vue';

    const props = defineProps(['status'])

    const classes = computed(() => {
        const classList = ['badge', 'rounded-sm']

        switch(props.status) {
            case 'Completed':
                classList.push('bg-green-700')
                break
            case 'Active':
                classList.push('bg-green-950')
                break
            case 'Inactive':
                classList.push('bg-red-950')
                break
            case 'Pending':
                classList.push('bg-amber-900')
                break
            default:
                classList.push('bg-gray-700')
        }
        return classList
    })
</script>

<template>
    <span :class="classes">
        {{ status }}
    </span>
</template>

<style scoped>
.badge {
    padding: 4px 10px;
    box-sizing: content-box;
    text-align: center;
}
</style>
