import { defineStore } from 'pinia';
import { reactive } from 'vue';

export const useProjectStore = defineStore('projects', () => {
    const filters = reactive(
        { name: '', status: '', start_date: '', end_date: '', creator_id: '', min_value: '', max_value: '', page: 1, page_size: 10, order_by: null }
    )

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
