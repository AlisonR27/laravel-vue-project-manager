<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ListChecks, Folder, LayoutGrid, ClipboardList, BriefcaseBusiness } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

// Page data
const page = usePage();
const auth = {
    user: page.props.auth.user as User,
};

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Projects',
        href: '/projects',
        icon: BriefcaseBusiness,
        children: [
            { title: 'New Project', href: '/projects/new' },
            { title: 'All Projects', href: '/projects' },
            { title: 'My Projects', href: '/projects?creator-id='+auth.user.id },
        ],
    },
    {
        title: 'Tasks',
        href: '/tasks',
        icon: ClipboardList,
        children: [
            { title: 'New Task', href: '/tasks/new' },
            { title: 'All Tasks', href: '/tasks' },
            { title: 'My Tasks', href: '/tasks?creator-id='+auth.user.id },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'My Projects',
        href: `/projects/?creator_id=${auth.user.id}`,
        icon: Folder,
    },
    {
        title: 'My Tasks',
        href: `/tasks/?creator_id=${auth.user.id}`,
        icon: ListChecks,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
