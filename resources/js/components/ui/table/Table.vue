<script setup lang="ts">
import { Card } from '@/components/ui/card';
import { AvatarImage } from '@/components/ui/avatar';
import { router } from '@inertiajs/vue3'
import { FileSearch, Edit, Trash, Search } from 'lucide-vue-next';
import UserInfo from '@/components/UserInfo.vue';
import { Button } from '@/components/ui/button';

type TableHeader = {
    key: string // chave usada para acessar o dado no row
    label: string // texto exibido no cabeçalho
    colspan?: number // opcional, se quiser mesclar colunas
}

type DynamicTableProps<T> = {
    headers: TableHeader[]
    rows: T[]
    rowKey: keyof T // qual campo será usado como chave única
    baseUrl?: string // opcional, para links automáticos,
    paginated: boolean,
    title?: string,
    type: "Projects" | "Tasks" | "Default"
}

const props = defineProps<DynamicTableProps<any>>()

const emitter = defineEmits(['project', 'task'])
function emitAction(event: 'project' | 'task', type: string, index: number) {
    // Get the id of the row and extract id data
    const  id  = props.rows[index]['id']

    emitter(event, {
        type,
        id
    })
}
</script>

<template>
    <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 px-8 py-6 md:min-h-min dark:border-sidebar-border">
        <h2 v-if="title" class="text-2xl font-bold text-accent-foreground"> {{ props.title }} </h2>
        <table class="w-full table-auto border-collapse border mt-5">
            <thead>
                <template v-if="$slots.thead">
                    <slot name="thead"></slot>
                </template>
                <tr v-else>
                    <th class="border py-3" v-for="header in headers" :key="header.key" :colspan="header.colspan || 1" > {{ header.label }} </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="rows.length > 0" v-for="(row, index) in rows" :key="row[rowKey]" class="py-5 border table-border">
                    <td v-for="header in headers" :key="header.key" :colspan="header.colspan || 1" :data-key="header.key" class="border px-4 py-3">
                        <template v-if="baseUrl && header.key == 'id'">
                            <a :href="`${baseUrl}/${row[header.key]}`">
                                {{ row[header.key] }}
                            </a>
                        </template>
                        <template v-else-if="header.key == 'user' || header.key == 'creator'">
                            <div class="flex flex-row py-2 px-3 items-center gap-3">
                                <UserInfo :user="row[header.key]"/>
                            </div>
                        </template>
                        <template v-else-if="header.key == 'project'">
                            <div class="py-2 px-3 border rounded-lg max-w-50 whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ row[header.key].name }}
                            </div>
                        </template>
                        <template v-else-if="header.key == 'actions'">
                            <template v-if="type == 'Projects'">
                                <Button title="Detail Project" @click="emitAction('project', 'detail', index)" class="cursor-pointer" size="sm" variant="ghost"><FileSearch/></Button>
                                <Button title="Edit Project" @click="emitAction('project', 'edit', index)" class="cursor-pointer" size="sm" variant="ghost"><Edit/></Button>
                                <Button title="Delete Project" @click="emitAction('project', 'delete', index)" class="cursor-pointer" size="sm" variant="ghost"><Trash/></Button>
                            </template>
                            <template v-else-if="type == 'Tasks'">
                                <Button title="Detail Task" @click="emitAction('task', 'detail', index)" class="cursor-pointer" size="sm" variant="ghost"><Search/></Button>
                            </template>
                        </template>
                        <template v-else>
                            {{ row[header.key] }}
                        </template>
                    </td>
                </tr>
                <span v-else>
                    No records of tasks found.
                </span>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
td[data-key="actions"] {
    height: 100%;
    white-space: nowrap;
}
</style>
