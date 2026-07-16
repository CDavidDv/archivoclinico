<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

defineProps({ solicitudes: Object, filtros: { type: Object, default: () => ({}) }, orden: { type: Object, default: () => ({}) } });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    aprobada: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    rechazada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');

const columns = [
    { key: 'folio', label: 'Folio', filter: 'text' },
    { key: 'modulo_solicitante', label: 'Módulo', filter: 'text' },
    { key: 'solicita', label: 'Solicita', filter: 'text' },
    { key: 'fecha_solicitud', label: 'Fecha', filter: 'date' },
    { key: 'detalles_count', label: 'Ítems', filter: false, sortable: true, sortKey: 'items' },
    { key: 'estatus', label: 'Estatus', filter: 'select', options: ['pendiente', 'aprobada', 'surtida', 'rechazada'].map((e) => ({ value: e, label: e })) },
];
</script>

<template>
    <AppLayout title="Solicitudes de Abastecimiento">
        <PageHeader title="Solicitudes de Abastecimiento">
            <template #actions>
                <Link :href="route('solicitudes.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Solicitud</Link>
            </template>
        </PageHeader>

        <Card title="Listado de Solicitudes">
            <DataTable :columns="columns" :paginator="solicitudes" route-name="solicitudes.index" :filters="filtros" :orden="orden" has-actions empty="No hay solicitudes.">
                <template #col-folio="{ row }"><span class="font-semibold">{{ row.folio }}</span></template>
                <template #col-modulo_solicitante="{ row }"><span class="capitalize">{{ row.modulo_solicitante }}</span></template>
                <template #col-solicita="{ row }">{{ row.usuario_solicita ? row.usuario_solicita.nombre_usuario : '—' }}</template>
                <template #col-fecha_solicitud="{ row }">{{ fecha(row.fecha_solicitud) }}</template>
                <template #col-estatus="{ row }"><span :class="badge(row.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ row.estatus }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('solicitudes.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
