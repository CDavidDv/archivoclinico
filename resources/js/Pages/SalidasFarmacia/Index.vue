<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({ salidas: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Salidas de Farmacia">
        <PageHeader title="Salidas de Farmacia">
            <template #actions>
                <Link :href="route('salidas_farmacia.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Salida</Link>
            </template>
        </PageHeader>

        <Card title="Historial de Salidas (mermas/ajustes)">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">ID</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                            <th class="px-3 py-2 font-medium">Usuario</th>
                            <th class="px-3 py-2 font-medium">Ítems</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="s in salidas.data" :key="s.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">#{{ s.id }}</td>
                            <td class="px-3 py-2">{{ fecha(s.fecha) }}</td>
                            <td class="px-3 py-2 capitalize">{{ s.tipo }}</td>
                            <td class="px-3 py-2">{{ s.usuario ? s.usuario.nombre_usuario : '—' }}</td>
                            <td class="px-3 py-2">{{ s.detalles_count }}</td>
                            <td class="px-3 py-2 text-right">
                                <Link :href="route('salidas_farmacia.show', s.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                            </td>
                        </tr>
                        <tr v-if="salidas.data.length === 0"><td colspan="6" class="px-3 py-6 text-center text-gray-400">No hay salidas.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="salidas.links" />
        </Card>
    </AppLayout>
</template>
