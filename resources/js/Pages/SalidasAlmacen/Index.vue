<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

defineProps({ salidas: Object, filtros: { type: Object, default: () => ({}) }, orden: { type: Object, default: () => ({}) } });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const tipoLabel = (t) => (t || '').replace('_', ' ');
const columns = [
    { key: 'id', label: 'ID', filter: 'text' },
    { key: 'fecha', label: 'Fecha', filter: 'date' },
    { key: 'tipo', label: 'Tipo', filter: 'text' },
    { key: 'area_destino', label: 'Área destino', filter: 'text' },
    { key: 'usuario', label: 'Usuario', filter: 'text' },
    { key: 'detalles_count', label: 'Ítems', filter: false, sortable: true, sortKey: 'items' },
];
</script>

<template>
    <AppLayout title="Salidas de Almacén">
        <PageHeader title="Salidas de Almacén">
            <template #actions>
                <Link :href="route('salidas_almacen.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Salida</Link>
            </template>
        </PageHeader>

        <Card title="Historial de Salidas">
            <DataTable :columns="columns" :paginator="salidas" route-name="salidas_almacen.index" :filters="filtros" :orden="orden" has-actions empty="No hay salidas.">
                <template #col-id="{ row }"><span class="font-semibold">#{{ row.id }}</span></template>
                <template #col-fecha="{ row }">{{ fecha(row.fecha) }}</template>
                <template #col-tipo="{ row }"><span class="capitalize">{{ tipoLabel(row.tipo) }}</span></template>
                <template #col-area_destino="{ row }">{{ row.area_destino || '—' }}</template>
                <template #col-usuario="{ row }">{{ row.usuario ? row.usuario.nombre_usuario : '—' }}</template>
                <template #actions="{ row }">
                    <Link :href="route('salidas_almacen.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
