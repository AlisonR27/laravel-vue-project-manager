<script setup lang="ts">
import { computed, onBeforeMount, ref } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import { CollapsibleTrigger, CollapsibleRoot, CollapsibleContent } from 'reka-ui'
import { Button } from '@/components/ui/button'
import UserSearchCombobox from '@/components/UserSearchCombobox.vue'
import { useUserStore } from '@/store/users'
import { useProjectStore } from '@/store/projects'
import { usePage } from '@inertiajs/vue3'
import CurrencyInput from '@/components/ui/input/CurrencyInput.vue';

const page = usePage()
const auth = { user: page.props.auth.user }

const projectsStore = useProjectStore()
const userStore = useUserStore()

const emit = defineEmits(['filter'])

const url = window.location.href
const query = Object.fromEntries(new URLSearchParams(url.split('?')[1] || ''))

// Inicializa filtros do projectsStore a partir da query
projectsStore.setFilters({
    search: query.search || '',
    status: query.status || '',
    start_date: query.start_date || '',
    end_date: query.end_date || '',
    min_value: query.min_value ? Number(query.min_value) : null,
    max_value: query.max_value ? Number(query.max_value) : null,
    creator_id: query.creator_id ? Number(query.creator_id) : null
})

const areAllEmpty = computed(() => {
    const f = projectsStore.filters
    return !(f.search || f.status || f.start_date || f.end_date || f.min_value || f.max_value || f.creator_id)
})

const isOpened = ref(!areAllEmpty.value)

const user = computed(() => {
    let currentUser = auth.user

    if (!userStore.initialized) userStore.initialize()

    if (projectsStore.filters.creator_id) {
        const found = userStore.findById(Number(projectsStore.filters.creator_id))
        if (found) currentUser = found
    }

    return currentUser
})

function submitFilters() {
    emit('filter', projectsStore.getOnlyFilled())
}

function updateCreator(creator: any) {
    projectsStore.setFilters({ creator_id: creator.id })
}

function clearFilters() {
    projectsStore.setFilters({
        search: '',
        status: '',
        start_date: '',
        end_date: '',
        min_value: null,
        max_value: null,
        creator_id: ''
    })
    emit('filter', {})
}

onBeforeMount(() => {
    if (!userStore.initialized) userStore.initialize()
    if (!areAllEmpty.value) isOpened.value = true
})
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
                        v-model="projectsStore.filters.search"
                        type="text"
                        placeholder="Search project name..."
                        class="w-full rounded border px-3 py-2 lg:w-8/12"
                    />

                    <select v-model="projectsStore.filters.status" class="w-full rounded border px-3 py-2 md:w-5/12 lg:w-3/12">
                        <option value="" disabled>No status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <UserSearchCombobox :user="user" @update="updateCreator" class="w-6/12" />

                    <input v-model="projectsStore.filters.start_date" type="date" class="rounded border px-3 py-1" />
                    <input v-model="projectsStore.filters.end_date" type="date" class="rounded border px-3 py-1" />
                    <CurrencyInput
                        placeholder="Minimum value"
                        v-model="projectsStore.filters.min_value"
                        :options="{
                            locale:'en-US',
                            currency: 'USD',
                            currencyDisplay: 'narrowSymbol',
                            hideCurrencySymbolOnFocus: false,
                            hideGroupingSeparatorOnFocus: false,
                            hideNegligibleDecimalDigitsOnFocus: false,
                            autoDecimalDigits: true,
                            useGrouping: true,
                            accountingSign: true
                        }"
                        class="border rounded p-2"
                    />
                    <CurrencyInput
                        placeholder="Maximum value"
                        v-model="projectsStore.filters.max_value"
                        :options="{
                            locale:'en-US',
                            currency: 'USD',
                            currencyDisplay: 'narrowSymbol',
                            hideCurrencySymbolOnFocus: false,
                            hideGroupingSeparatorOnFocus: false,
                            hideNegligibleDecimalDigitsOnFocus: false,
                            autoDecimalDigits: true,
                            useGrouping: true,
                            accountingSign: true
                        }"
                        class=" border rounded p-2"
                    />
                    <div class="flex w-full flex-row gap-2">
                        <Button variant="outline" :disabled="areAllEmpty" @click.prevent="clearFilters"> Clear Filters </Button>
                        <Button type="submit" variant="default" :disabled="areAllEmpty"> Apply Filters </Button>
                    </div>
                </form>
            </CollapsibleContent>
        </CollapsibleRoot>
    </div>
</template>
