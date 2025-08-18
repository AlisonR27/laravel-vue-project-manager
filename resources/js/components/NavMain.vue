<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next'


defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const openMenus = ref<string[]>([]); // track which menus are expanded

function toggleMenu(title: string) {
    if (openMenus.value.includes(title)) {
        openMenus.value = openMenus.value.filter((t) => t !== title);
    } else {
        openMenus.value.push(title);
    }
}
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <!-- Collapsible parent -->
                <template v-if="item.children">
                    <SidebarMenuSubButton
                        as-child
                        :is-active="openMenus.includes(item.title)"
                        @click="toggleMenu(item.title)"
                        class="py-2 h-auto"
                    >
                        <div class="flex items-center justify-between cursor-pointer w-full">
                            <div class="flex items-center gap-2">
                                <component :is="item.icon" v-if="item.icon" :size="16" />
                                <span>{{ item.title }}</span>
                            </div>
                            <!-- Chevron that rotates -->
                            <ChevronDown
                                class="transition-transform duration-200"
                                :class="{ 'rotate-180': openMenus.includes(item.title) }"
                            />
                        </div>
                    </SidebarMenuSubButton>

                    <!-- Children -->
                    <SidebarMenuSub v-if="openMenus.includes(item.title)">
                        <SidebarMenuSubItem v-for="sub in item.children" :key="sub.title">
                            <SidebarMenuButton as-child :is-active="sub.href === page.url">
                                <Link :href="sub.href">
                                    <span>{{ sub.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuSubItem>
                    </SidebarMenuSub>
                </template>

                <!-- Normal link -->
                <template v-else>
                    <SidebarMenuButton as-child :is-active="item.href === page.url">
                        <Link :href="item.href">
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </template>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
