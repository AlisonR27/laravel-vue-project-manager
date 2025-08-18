<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next';
import { CollapsibleTrigger, CollapsibleRoot, CollapsibleContent, Combobox } from 'reka-ui';
import { Button } from '@/components/ui/button';
import { router, usePage } from '@inertiajs/vue3';
import type { User } from '@/types';
import UserSearchCombobox from '@/components/UserSearchCombobox.vue';
import { useUserStore } from '@/store/users';

const page = usePage();

const auth = {
    user: page.props.auth.user as User,
};

const emit = defineEmits(['filter']);

const url = window.location.href;

const query = Object.fromEntries(new URLSearchParams(url.split('?')[1] || ''));

// filtros
const search = ref(query.search || '');
const status = ref(query.status || '');
const start_date = ref(query.start_date || '');
const end_date = ref(query.end_date || '');
const min_value = ref<number | null>(Number(query.min_value) || null);
const max_value = ref<number | null>(Number(query.max_value) || null);
const creator_id = ref<number | null>(Number(query.creator_id) || null);

const submitFilters = () => {
    const filters = {
        search: search.value,
        status: status.value,
        start_date: start_date.value,
        end_date: end_date.value,
        min_value: min_value.value,
        max_value: max_value.value,
        creator_id: creator_id.value,
    };
    const filteredFilterFields = Object.fromEntries(
        Object.entries(filters).filter(([key, value]: [string, any]) => {
            return value != '' && value != null && typeof value != 'undefined';
        }),
    );

    emit('filter', filteredFilterFields);
};

const areAllEmpty = computed(() => {
    return !(search.value || status.value || start_date.value || end_date.value || min_value.value || max_value.value || creator_id.value);
});

const isOpened = ref(!areAllEmpty.value);

const user = computed(() => {
    const { initialized, initialize, findById } = useUserStore()

    // fallback
    let currentUser = auth.user

    if (!initialized) {
        initialize()
    }

    if (creator_id.value) {
        const found = findById(creator_id.value)
        if (found) {
            currentUser = found
        }
    }

    return currentUser

})

// const user = creator_id.value != null ? useUserStore().findById(creator_id.value): null
// const user = computed(() => (creator_id.value != null ? useUserStore().findById(creator_id.value) : null));

function updateCreator(creator: any) {
    creator_id.value = creator.id;
}

function clearFilters() {
    search.value = ''
    status.value = ''
    start_date.value = ''
    end_date.value = ''
    min_value.value = null
    max_value.value = null
    creator_id.value = null

    emit('filter', {}) // optional: immediately notify parent
}

onBeforeMount(() => {
    if (!useUserStore().initialized) {
        useUserStore().initialize()
    }
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
                    <input v-model="search" type="text" placeholder="Search project name..." class="w-full rounded border px-3 py-2 lg:w-8/12" />

                    <select v-model="status" class="w-full rounded border px-3 py-2 md:w-5/12 lg:w-3/12">
                        <option value="" disabled>No status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <UserSearchCombobox :user="user" @update="updateCreator" class="w-6/12" />

                    <input v-model="start_date" type="date" class="rounded border px-3 py-1" />

                    <input v-model="end_date" type="date" class="rounded border px-3 py-1" />

                    <input v-model.number="min_value" type="number" min="0" placeholder="Min value" class="rounded border px-3 py-1" />

                    <input v-model.number="max_value" type="number" min="0" placeholder="Max value" class="rounded border px-3 py-1" />
                    <div class="flex w-full flex-row gap-2">
                        <Button variant="outline" :disabled="areAllEmpty" @click.prevent="clearFilters"> Clear Filters </Button>
                        <Button type="submit" variant="default" class="" :disabled="areAllEmpty"> Apply Filters </Button>
                    </div>
                </form>
            </CollapsibleContent>
        </CollapsibleRoot>
    </div>
</template>
