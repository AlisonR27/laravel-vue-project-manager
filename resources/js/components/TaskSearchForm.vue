<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import { CollapsibleTrigger, CollapsibleRoot, CollapsibleContent } from 'reka-ui'
import { Button } from '@/components/ui/button'
import UserSearchCombobox from '@/components/UserSearchCombobox.vue'
import { useUserStore } from '@/store/users'
import { useTasksStore } from '@/store/tasks'
import { usePage } from '@inertiajs/vue3'

const page = usePage();
const auth = { user: page.props.auth.user }

const tasksStore = useTasksStore()

const emit = defineEmits(['filter'])

const areAllEmpty = computed(() => {
    return !(tasksStore.filters.title || tasksStore.filters.status || tasksStore.filters.project_id || tasksStore.filters.creator_id)
});
const isOpened = ref(false);

const userStore = useUserStore();
const user = computed(() => {
    let currentUser = auth.user

    if (!userStore.initialized) {
        userStore.initialize()
    }

    if (tasksStore.filters.creator_id) {
        const found = userStore.findById(Number(tasksStore.filters.creator_id))
        if (found) currentUser = found
    }

    return currentUser
});



function submitFilters() {
    emit('filter', tasksStore.getOnlyFilled())
}

function updateCreator(creator: any) {
    tasksStore.setFilters({ creator_id: creator.id })
}

function clearFilters() {
    tasksStore.setFilters({
        title: '',
        status: '',
        due_date: '',
        creator_id: '',
    });
    emit('filter', {})
}

onBeforeMount(() => {
    if (!userStore.initialized) userStore.initialize()
    if (!areAllEmpty.value) isOpened.value = true
});
</script>
<template>
    <div class="relative flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
        <CollapsibleRoot v-model:open="isOpened">
            <CollapsibleTrigger class="flex w-full cursor-pointer flex-row items-center justify-between">
                <h3 class="text-xl font-bold text-accent-foreground">Search & Filters</h3>
                <ChevronDown :size="21" class="transition-transform" :class="isOpened ? 'rotate-180' : ''" />
            </CollapsibleTrigger>
            <CollapsibleContent class="pt-5 transition-all data-[state=closed]:animate-collapsible-up data-[state=open]:animate-collapsible-down">
                <form @submit.prevent="submitFilters" class="flex flex-row flex-wrap gap-4">
                    <input
                        v-model="tasksStore.filters.title"
                        type="text"
                        placeholder="Search task name..."
                        class="w-full rounded border px-3 py-2 lg:w-8/12"
                    />

                    <select v-model="tasksStore.filters.status" class="w-full rounded border px-3 py-2 md:w-5/12 lg:w-3/12">
                        <option value="" disabled>No status</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                        <option value="Complete">Complete</option>
                    </select>

                    <UserSearchCombobox :user="user" @update="updateCreator" class="w-6/12" />

                    <div class="flex w-full flex-row gap-2">
                        <Button variant="outline" :disabled="areAllEmpty" @click.prevent="clearFilters"> Clear Filters </Button>
                        <Button type="submit" variant="default" class="" :disabled="areAllEmpty"> Apply Filters </Button>
                    </div>
                </form>
            </CollapsibleContent>
        </CollapsibleRoot>
    </div>
</template>
