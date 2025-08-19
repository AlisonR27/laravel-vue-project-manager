<script setup async lang="ts">
import { router } from '@inertiajs/vue3'
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogClose } from '@/components/ui/dialog'
import { Ref, ref, watch } from 'vue'
import { Skeleton } from '@/components/ui/skeleton'
import { Button } from '@/components/ui/button';

import { ExternalLink } from 'lucide-vue-next';

const emitter = defineEmits(['close'])
const props = defineProps(['isOpen', 'taskId'])

const loading = ref(true)

const taskDetails = ref(null) as Ref<any>

const openModal = ref(false)
watch( openModal, (open) => {
    if (!open) emitter('close')
})
watch(
    () => props.isOpen,
    async (open) => {
        if (open && props.taskId) {
            loading.value = true
            openModal.value = true
            try {
                const response = await fetch(`/tasks/${props.taskId}/async`)
                const data = await response.json()
                taskDetails.value = data
            } catch (err) {
                console.error(err);
                taskDetails.value = null;
            } finally {
                loading.value = false;
            }
        } else {}
    },
);

function openDetailPage() {
    router.visit(`/tasks/${props.taskId}`)
}
</script>

<template>
    <Dialog v-model:open="openModal" modal>
        <DialogContent>
            <DialogHeader class="space-y-3">
                <DialogTitle>
                    <DialogDescription>
                        Project Details
                        <Button title="Abrir página da tarefa" @click="openDetailPage" class="cursor-pointer" variant="ghost"> <ExternalLink/> </Button>
                    </DialogDescription>
                </DialogTitle>
                <div v-if="loading" class="mt-5 animate-pulse">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- 6 cells: 3 rows × 2 cols -->
                        <div v-for="i in 6" :key="i" class="space-y-2">
                            <!-- label (half width) -->
                            <Skeleton class="h-4 w-1/2" />
                            <!-- content (full width) -->
                            <Skeleton class="h-5 w-full" />
                        </div>
                    </div>
                </div>
                <template v-else>
                    <DialogDescription v-if="taskDetails">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <!-- Informações da Task -->
                            <div>
                                <span class="font-medium text-gray-500">ID:</span>
                                <span class="block">{{ taskDetails.id }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Título:</span>
                                <span class="block">{{ taskDetails.title }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Status:</span>
                                <span class="block">{{ taskDetails.status }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Criado em:</span>
                                <span class="block">{{ new Date(taskDetails.created_at).toLocaleString() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Data Máxima:</span>
                                <span class="block">{{ new Date(taskDetails.due_date).toLocaleString() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Atualizado em:</span>
                                <span class="block">{{ new Date(taskDetails.updated_at).toLocaleString() }}</span>
                            </div>

                            <!-- Informações do Projeto -->
                            <div>
                                <span class="font-medium text-gray-500">Projeto:</span>
                                <span class="block">{{ taskDetails.project?.name }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Status do Projeto:</span>
                                <span class="block">{{ taskDetails.project?.status }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Valor do Projeto:</span>
                                <span class="block">{{ taskDetails.project?.value }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Início do Projeto:</span>
                                <span class="block">{{ taskDetails.project?.start_date }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Término do Projeto:</span>
                                <span class="block">{{ taskDetails.project?.end_date }}</span>
                            </div>
                        </div>
                    </DialogDescription>
                    <div class="w-full text-center" v-else>Couldn't find any details to this task</div>
                </template>
            </DialogHeader>
        </DialogContent>
    </Dialog>
</template>

<style scoped></style>
