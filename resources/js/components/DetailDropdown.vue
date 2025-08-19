<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { DropdownMenuArrow, DropdownMenuContent, DropdownMenuItem, DropdownMenuRoot, DropdownMenuSeparator, DropdownMenuTrigger } from 'reka-ui';
import { Edit, EllipsisVertical, Trash } from 'lucide-vue-next';

const emit = defineEmits(['delete'])
const props = defineProps(['type', 'id'])
</script>

<template>
    <DropdownMenuRoot class="absolute right-0">
        <DropdownMenuTrigger as-child>
            <Button class="absolute top-3 right-3 cursor-pointer" variant="ghost"> <EllipsisVertical /> </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent side="bottom" align="end" class="rounded-md p-2">
            <DropdownMenuItem class="DropDownMenuItem rounded-md" role="menuitem" @select.prevent="router.visit(`/${type}/${id}/edit`)">
                <Edit :size="16" /> Edit
            </DropdownMenuItem>

            <DropdownMenuSeparator />

            <DropdownMenuItem class="DropDownMenuItem Destructive rounded-md" role="menuitem" @select.prevent.stop="$emit('delete', id)">
                <Trash :size="16" /> Delete
            </DropdownMenuItem>

            <DropdownMenuArrow />
        </DropdownMenuContent>
    </DropdownMenuRoot>
</template>

<style scoped>
[data-reka-popper-content-wrapper] {
    background: var(--sidebar-background);
    filter: drop-shadow(0 0 1px  white);
    z-index: 10 !important;
    border-radius:0.25rem;
}

[data-reka-popper-content-wrapper]:deep(div:only-child) {
}

[data-reka-popper-content-wrapper]:deep(span, span *) {
    border-color: var(--sidebar-background) ;
    fill: var(--sidebar-background) ;
}
[data-reka-popper-content-wrapper]:deep(span) {
    left: unset;
    right: 15px !important;
}

.DropDownMenuItem {
    padding-block: calc(var(--spacing) * 3);
    padding-inline: calc(var(--spacing) * 6);
    display: flex;
    flex-direction: row;
    justify-content: start;
    cursor: pointer;
    align-items: center;
    gap: 0.8rem;
    z-index:9999;
}
.DropDownMenuItem:hover {
    background-color: var(--sidebar-accent);
}
.DropDownMenuItem.Destructive:hover {
    background: var(--destructive);
}
</style>
