<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import type { User } from '@/types';
import {
    ComboboxAnchor,
    ComboboxContent,
    ComboboxEmpty,
    ComboboxInput,
    ComboboxItem,
    ComboboxRoot,
    ComboboxSeparator,
    ComboboxTrigger,
    ComboboxViewport,
    ComboboxItemIndicator
} from 'reka-ui';
import { TextSearch, XCircle, Check } from 'lucide-vue-next';
import { AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { computed, ref, watch } from 'vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { useUserStore } from '@/store/users';

const { getInitials } = useInitials();
const userStore = useUserStore();

// Props and emitters
const props = defineProps<{
    user: User;
}>();
const emit = defineEmits(['update']);

// Page data
const page = usePage();
const auth = {
    user: page.props.auth.user as User,
};

const editing = ref(false);

// If it has a user on the props (query), use it, in other case use the logged user
const selectedUserId = ref(props.user.id as number | null);
const selectedUser = ref(props.user as User | null);

const users = computed(() => userStore.getUsersExceptById(auth.user.id));

const userListLoading = ref(true);

function handleOpenCombo() {
    if (!userStore.initialized) {
        userListLoading.value = true;
        userStore.fetchUsers();
        userListLoading.value = false;
    } else {
        userListLoading.value = false;
    }
}
function handleUpdate(id: number) {
    selectedUserId.value = id;
    selectedUser.value = userStore.findById(id);
    editing.value = false;
    emit('update', selectedUser.value);
}

function removeSelected() {
    editing.value = true;
    selectedUserId.value = null;
    selectedUser.value = null;
}
watch(() => props.user, () => {
    selectedUser.value = props.user
});
</script>

<template>
    <ComboboxRoot class="relative" v-model="selectedUserId" @update:open="handleOpenCombo" @update:modelValue="handleUpdate">
        <ComboboxAnchor class="relative">
            <div
                v-if="!editing && selectedUser != null"
                role="button"
                aria-description="Open user search input"
                class="flex w-full flex-row items-center gap-2 rounded border px-3 py-1"
                @click="editing = true"
            >
                <AvatarImage v-if="selectedUser.avatar" :src="selectedUser.avatar!" :alt="selectedUser.name" />
                <div class="aspect-square w-7 rounded bg-gray-300 p-1 text-center text-black" v-else>{{ getInitials(selectedUser.name) }}</div>
                {{ selectedUser.name }}
                <button @click.prevent="removeSelected" class="absolute top-3 right-2 cursor-pointer hover:fill-gray-800">
                    <XCircle :size="18" />
                </button>
            </div>
            <ComboboxInput v-else class="w-full rounded border px-3 py-1" placeholder="Search for an creator" />
            <ComboboxTrigger v-if="editing" class="absolute top-3 right-2 cursor-pointer hover:fill-gray-800">
                <TextSearch :size="18" />
            </ComboboxTrigger>
        </ComboboxAnchor>
        <ComboboxContent class="absolute z-10 w-full border bg-background p-3">
            <ComboboxViewport class="text-sm">
                <ComboboxEmpty />
                <ComboboxItem
                    class="flex cursor-pointer flex-row items-center gap-2 rounded p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800"
                    :value="auth.user.id"
                >
                    <ComboboxItemIndicator
                        class="absolute left-0 w-[25px] inline-flex items-center justify-center"
                    >
                        <Check :size="20"/>
                    </ComboboxItemIndicator>
                    <AvatarImage v-if="auth.user.avatar" :src="auth.user.avatar!" :alt="auth.user.name" />
                    <div class="rounded bg-gray-300 p-1 text-black" v-else>{{ getInitials(auth.user.name) }}</div>
                    {{ auth.user.name }} (me)
                </ComboboxItem>
                <template v-if="!userListLoading && users.length > 0">
                    <ComboboxSeparator class="m-[5px] h-[1px] bg-sidebar-border" />
                    <ComboboxItem
                        v-for="userItem in users"
                        :key="userItem.id"
                        :value="userItem.id"
                        class="flex cursor-pointer flex-row items-center gap-2 rounded p-2 hover:bg-zinc-200 dark:hover:bg-zinc-800"
                    >
                        <ComboboxItemIndicator
                            class="absolute left-0 w-[25px] inline-flex items-center justify-center"
                        >
                            <Check :size="20"/>
                        </ComboboxItemIndicator>
                        <AvatarImage v-if="userItem.avatar" :src="userItem.avatar!" :alt="userItem.name" />
                        <div class="aspect-square w-7 rounded bg-gray-300 p-1 text-center text-black" v-else>{{ getInitials(userItem.name) }}</div>
                        {{ userItem.name }}
                    </ComboboxItem>
                </template>
                <template v-else-if="userListLoading">
                    <ComboboxSeparator class="m-[5px] h-[1px] bg-sidebar-border" />
                    <div class="flex w-full justify-center py-5">
                        <Spinner class="" />
                    </div>
                </template>
            </ComboboxViewport>
        </ComboboxContent>
    </ComboboxRoot>
</template>

<style scoped></style>
