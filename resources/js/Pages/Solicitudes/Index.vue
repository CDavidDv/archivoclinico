<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({ solicitudes: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    aprobada: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    rechazada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');
</script>

<template>
    <AppLayout title="Solicitudes de Abastecimiento">
        <PageHeader title="Solicitudes de Abastecimiento">
            <template #actions>
                <Link :href="route('solicitudes.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Solicitud</Link>
            </template>
        </PageHeader>

        <Card title="Listado de Solicitudes">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Folio</th>
                            <th class="px-3 py-2 font-medium">Módulo</th>
                            <th class="px-3 py-2 font-medium">Solicita</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Ítems</th>
                            <th class="px-3 py-2 font-medium">Estatus</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="s in solicitudes.data" :key="s.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ s.folio }}</td>
                            <td class="px-3 py-2 capitalize">{{ s.modulo_solicitante }}</td>
                            <td class="px-3 py-2">{{ s.usuario_solicita ? s.usuario_solicita.nombre_usuario : '—' }}</td>
                            <td class="px-3 py-2">{{ fecha(s.fecha_solicitud) }}</td>
                            <td class="px-3 py-2">{{ s.detalles_count }}</td>
                            <td class="px-3 py-2">
                                <span :class="badge(s.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ s.estatus }}</span>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <Link :href="route('solicitudes.show', s.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                            </td>
                        </tr>
                        <tr v-if="solicitudes.data.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay solicitudes.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="solicitudes.links" />
        </Card>
    </AppLayout>
</template>
