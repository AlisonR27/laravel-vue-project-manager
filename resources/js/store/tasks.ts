import { defineStore } from 'pinia';
import { reactive } from 'vue';

export const useTasksStore = defineStore('tasks', () => {
    const filters = reactive({ title: '', status: '', project_id: '', due_date: '', creator_id: '', page: 1, page_size: 10 })

    function setFilters(newFilters: Partial<typeof filters>) {
        Object.assign(filters, newFilters)
    }

    function getOnlyFilled() {
        return Object.fromEntries(
            Object.entries(filters).filter(([key, value]) => {
                return value !== '' && value !== null && value !== undefined;
            })
        )
    }

    return { filters, setFilters, getOnlyFilled }
});
