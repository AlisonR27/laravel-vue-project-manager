<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Components
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogDescription, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const emit = defineEmits<{ (e: 'close'): void }>();

const props = defineProps(['type', 'itemId', 'open']);

const form = useForm({
    id: props.id,
});

const deleteT = (e: Event) => {
    const { type, itemId } = props

    e.preventDefault();

    form.delete(route(`${type}.destroy`, { id: itemId }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => window.alert(`It wasn't possible to delete your ${type}, try again later`),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    router.visit(`/${props.type}s`);
};
</script>

<template>
    <div class="space-y-6">
        <Dialog :open="props.open" @update:open="(val) => !val && emit('close')">
            <DialogTrigger as-child>
                <slot name="trigger"> </slot>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete this {{ type }}?</DialogTitle>
                </DialogHeader>
                <DialogDescription v-if="type == 'project'">
                    Doing this, you will delete all child tasks as well. This action is irreversible.
                </DialogDescription>
                <DialogFooter class="mt-4 gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal"> Cancel </Button>
                    </DialogClose>

                    <Button type="button" class="hover:900 cursor-pointer" @click="deleteT" variant="destructive"> Delete {{ type }} </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
