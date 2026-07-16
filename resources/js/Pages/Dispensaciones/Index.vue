<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

defineProps({ dispensaciones: Object, filtros: { type: Object, default: () => ({}) } });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
const columns = [
    { key: 'id', label: 'ID', filter: 'text' },
    { key: 'receta', label: 'Receta', filter: 'text' },
    { key: 'derechohabiente', label: 'Derechohabiente', filter: 'text' },
    { key: 'fecha', label: 'Fecha', filter: 'date' },
    { key: 'usuario', label: 'Usuario', filter: 'text' },
];
</script>

<template>
    <AppLayout title="Dispensaciones">
        <PageHeader title="Dispensaciones" />

        <Card title="Historial de Dispensaciones">
            <DataTable :columns="columns" :paginator="dispensaciones" route-name="dispensaciones.index" :filters="filtros" has-actions empty="No hay dispensaciones.">
                <template #col-id="{ row }"><span class="font-semibold">#{{ row.id }}</span></template>
                <template #col-receta="{ row }">{{ row.receta ? row.receta.folio : '—' }}</template>
                <template #col-derechohabiente="{ row }">{{ row.receta && row.receta.derecho_habiente ? nom(row.receta.derecho_habiente) : '—' }}</template>
                <template #col-fecha="{ row }">{{ fecha(row.fecha) }}</template>
                <template #col-usuario="{ row }">{{ row.usuario ? row.usuario.nombre_usuario : '—' }}</template>
                <template #actions="{ row }">
                    <Link :href="route('dispensaciones.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
