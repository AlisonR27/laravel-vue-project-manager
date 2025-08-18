<script setup lang="ts">
import { PaginationEllipsis, PaginationFirst, PaginationLast, PaginationList, PaginationListItem, PaginationNext, PaginationPrev, PaginationRoot } from 'reka-ui'
import { ChevronLeft, ChevronsLeft, ChevronRight, ChevronsRight } from 'lucide-vue-next'
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    total: {
        type: Number
    },
    page_size: {
        type: Number,
        default: 10
    },
    currentPage: {
        type: Number,
        default: 1
    }
})

const pageSize = ref(props.page_size)
const page = ref(props.currentPage)

const pageSizes = [
    8,
    10,
    15,
    20
]

</script>

<template>
    <PaginationRoot
        v-model:page="page"
        :total="Number(total)"
        :sibling-count="1"
        :items-per-page="Number(page_size)"
        show-edges
        :default-page="1"
        @update:page="$emit('paginate', page)"
    >
        <PaginationList
            v-slot="{ items }"
            class="flex items-center gap-1 text-stone-700 dark:text-white"
        >
            <PaginationFirst class="cursor-pointer w-9 h-9  flex items-center justify-center bg-transparent hover:bg-white dark:hover:bg-stone-700/70 transition disabled:opacity-50 rounded-lg">
                <ChevronsLeft />
            </PaginationFirst>
            <PaginationPrev class="cursor-pointer w-9 h-9  flex items-center justify-center bg-transparent hover:bg-white dark:hover:bg-stone-700/70 transition mr-4  disabled:opacity-50 rounded-lg">
                <ChevronLeft />
            </PaginationPrev>
            <template v-for="(page, index) in items">
                <PaginationListItem
                    v-if="page.type === 'page'"
                    :key="index"
                    class="cursor-pointer w-9 h-9 border dark:border-stone-800 rounded-lg data-[selected]:!bg-white data-[selected]:text-dark data-[selected]:shadow-sm data-[selected]:text-black data-[selected]:hover:bg-black hover:bg-white dark:hover:bg-stone-700/70 transition"
                    :value="page.value"
                >
                    {{ page.value }}
                </PaginationListItem>
                <PaginationEllipsis
                    v-else
                    :key="page.type"
                    :index="index"
                    class="cursor-pointer w-9 h-9 flex items-center justify-center"
                >
                    &#8230;
                </PaginationEllipsis>
            </template>
            <PaginationNext class="cursor-pointer w-9 h-9  flex items-center justify-center bg-transparent hover:bg-white dark:hover:bg-stone-700/70 transition ml-4 disabled:opacity-50 rounded-lg">
                <ChevronRight />
            </PaginationNext>
            <PaginationLast class="cursor-pointer w-9 h-9  flex items-center justify-center bg-transparent hover:bg-white dark:hover:bg-stone-700/70 transition disabled:opacity-50 rounded-lg">
                <ChevronsRight/>
            </PaginationLast>
        </PaginationList>
        <div class="ml-auto">
            <label for="page-size" class="mr-2">Show per page:</label>
            <select id="page-size" v-model="pageSize" class="px-3 py-2 border rounded" @change="$emit('size',pageSize)">
                <option v-for="pageSz of pageSizes" :key="pageSz" :value="pageSz"> {{ pageSz }} items </option>
            </select>
        </div>

    </PaginationRoot>
</template>

<style scoped>

</style>
