<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

defineProps({ transferencias: Object, filtros: { type: Object, default: () => ({}) }, orden: { type: Object, default: () => ({}) } });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const columns = [
    { key: 'folio', label: 'Folio', filter: 'text' },
    { key: 'fecha', label: 'Fecha', filter: 'date' },
    { key: 'destino', label: 'Destino', filter: 'text' },
    { key: 'solicitud', label: 'Solicitud', filter: 'text' },
    { key: 'usuario', label: 'Usuario', filter: 'text' },
    { key: 'detalles_count', label: 'Ítems', filter: false, sortable: true, sortKey: 'items' },
];
</script>

<template>
    <AppLayout title="Transferencias">
        <PageHeader title="Transferencias">
            <template #actions>
                <Link :href="route('transferencias.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Transferencia</Link>
            </template>
        </PageHeader>

        <Card title="Historial de Transferencias">
            <DataTable :columns="columns" :paginator="transferencias" route-name="transferencias.index" :filters="filtros" :orden="orden" has-actions empty="No hay transferencias.">
                <template #col-folio="{ row }"><span class="font-semibold">{{ row.folio }}</span></template>
                <template #col-fecha="{ row }">{{ fecha(row.fecha) }}</template>
                <template #col-destino="{ row }"><span class="capitalize">{{ row.destino }}{{ row.area_destino ? ` — ${row.area_destino}` : '' }}</span></template>
                <template #col-solicitud="{ row }">{{ row.solicitud ? row.solicitud.folio : '—' }}</template>
                <template #col-usuario="{ row }">{{ row.usuario ? row.usuario.nombre_usuario : '—' }}</template>
                <template #actions="{ row }">
                    <Link :href="route('transferencias.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
