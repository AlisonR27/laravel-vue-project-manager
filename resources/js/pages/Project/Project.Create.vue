<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, User } from '@/types';
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent } from '@/components/ui/dialog';


import { ref } from 'vue';
import CurrencyInput from '@/components/ui/input/CurrencyInput.vue';

const page = usePage();

const auth = {
    user: page.props.auth.user as User,
};

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Projects',
        href: '/projects',
    },
    {
        title: 'Create',
        href: '#'
    }
] as BreadcrumbItem[]

const today = new Date()
const form = useForm({
    name:  '',
    start_date:  today.toISOString().split('T')[0],
    end_date:  `${today.getFullYear()}-0${today.getMonth()+1}-${today.getDate()+1}`,
    value:  0.01,
    status: 'Active',
    creator_id: auth.user.id
})

const submit = () => {
    form.post(route('project.create'))
}

const statusOptions = [
    { label: 'Active', value: 'Active' },
    { label: 'Inactive', value: 'Inactive' },
]
const isAlertClosed = ref(false)
</script>

<template>
    <Head :title="`Create a new Project`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold mb-4">Editar Projeto</h1>
            <form @submit.prevent="submit" class="gap-4 grid grid-cols-3">
                <div class="col-span-3">
                    <label for="name" class="block font-medium">Name</label>
                    <input
                        id="name"
                        type="text"
                        placeholder="My project"
                        v-model="form.name"
                        maxlength="60"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                </div>

                <div>
                    <label for="start_date" class="block font-medium">Start Date</label>
                    <input
                        id="start_date"
                        type="date"
                        v-model="form.start_date"
                        class="w-full border rounded p-2 "
                    />
                    <div v-if="form.errors.start_date" class="text-red-600 text-sm">{{ form.errors.start_date }}</div>
                </div>

                <div>
                    <label for="end_date" class="block font-medium">Due Date</label>
                    <input
                        id="end_date"
                        type="date"
                        v-model="form.end_date"
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.end_date" class="text-red-600 text-sm">{{ form.errors.end_date }}</div>
                </div>

                <div class="w-auto">
                    <label for="value" class="block font-medium">Value</label>
                    <CurrencyInput
                        placeholder="$ 0.00"
                        v-model="form.value"
                        max="10000000"
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
                        class="w-full border rounded p-2"
                    />
                    <div v-if="form.errors.value" class="text-red-600 text-sm">{{ form.errors.value }}</div>
                </div>

                <!-- Status -->
                <div class="col-span-3">
                    <label for="status" class="block font-medium">Status</label>
                    <select
                        id="status"
                        v-model="form.status"
                        class="w-full border rounded p-2"
                    >
                        <option class="dark:text-white dark:bg-zinc-900 dark:hover:!bg-zinc-400 py-4" value="Active">Active</option>
                        <option class="dark:text-white dark:bg-zinc-900 dark:hover:!bg-zinc-400 py-4" value="Inactive">Inactive</option>
                    </select>
                    <div v-if="form.errors.status" class="text-red-600 text-sm">{{ form.errors.status }}</div>
                </div>
                <!-- Botão -->
                <Button
                    variant="default"
                    type="submit"
                    :disabled="form.processing"
                    class="cursor-pointer col-start-1"
                >
                    Salvar
                </Button>
            </form>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
