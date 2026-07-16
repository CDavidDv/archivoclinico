<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

const props = defineProps({
    movimientos: Object,
    usuarios: { type: Array, default: () => [] },
    filtros: { type: Object, default: () => ({}) },
});

const fecha = (f) => (f ? new Date(f).toLocaleString('es-MX') : '—');

const columns = [
    { key: 'fecha_accion', label: 'Fecha', filter: 'date', filterKey: 'fecha' },
    { key: 'usuario', label: 'Usuario', filter: 'select', filterKey: 'usuario', options: props.usuarios.map((u) => ({ value: u.id, label: u.nombre_usuario })) },
    { key: 'accion', label: 'Acción', filter: 'text' },
    { key: 'tabla_afectada', label: 'Tabla', filter: 'text', filterKey: 'tabla' },
    { key: 'id_registro_afectado', label: 'Registro', filter: 'text', filterKey: 'registro' },
];
</script>

<template>
    <AppLayout title="Bitácora de Movimientos">
        <PageHeader title="Bitácora de Movimientos" />

        <Card title="Registros">
            <DataTable :columns="columns" :paginator="movimientos" route-name="movimientos.index" :filters="filtros" empty="Sin movimientos.">
                <template #col-fecha_accion="{ row }">{{ fecha(row.fecha_accion) }}</template>
                <template #col-usuario="{ row }">{{ row.usuario ? row.usuario.nombre_usuario : '—' }}</template>
                <template #col-accion="{ row }"><span class="capitalize">{{ row.accion }}</span></template>
                <template #col-id_registro_afectado="{ row }">{{ row.id_registro_afectado ?? '—' }}</template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
