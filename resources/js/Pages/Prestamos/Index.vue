<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ prestamos: Object, filtros: { type: Object, default: () => ({}) } });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    devuelto: 'bg-emerald-100 text-emerald-800',
    vencido: 'bg-red-100 text-red-700',
    pendiente: 'bg-amber-100 text-amber-800',
}[e] || 'bg-gray-100 text-gray-700');

const columns = [
    { key: 'expediente', label: 'Expediente', filter: 'text' },
    { key: 'medico', label: 'Médico', filter: 'text' },
    { key: 'area_destino', label: 'Área', filter: 'text' },
    { key: 'fecha_salida', label: 'Salida', filter: 'date' },
    { key: 'fecha_regreso', label: 'Regreso', filter: 'date' },
    { key: 'estatus', label: 'Estatus', filter: 'select', options: ['pendiente', 'devuelto', 'vencido'].map((e) => ({ value: e, label: e })) },
];

const eliminar = async (p) => {
    if (await confirmarEliminar('¿Eliminar este préstamo?')) router.delete(route('prestamos.destroy', p.id));
};
</script>

<template>
    <AppLayout title="Préstamos">
        <PageHeader title="Préstamos de Expedientes">
            <template #actions>
                <Link :href="route('prestamos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Préstamo</Link>
            </template>
        </PageHeader>

        <Card title="Listado de Préstamos">
            <DataTable :columns="columns" :paginator="prestamos" route-name="prestamos.index" :filters="filtros" has-actions empty="No hay préstamos registrados.">
                <template #col-expediente="{ row }"><span class="font-semibold">{{ row.expediente ? row.expediente.codigo : '—' }}</span></template>
                <template #col-medico="{ row }">{{ row.medico ? `${row.medico.nombre} ${row.medico.apellido_paterno}` : '—' }}</template>
                <template #col-fecha_salida="{ row }">{{ fecha(row.fecha_salida) }}</template>
                <template #col-fecha_regreso="{ row }">{{ fecha(row.fecha_regreso) }}</template>
                <template #col-estatus="{ row }"><span :class="badge(row.estatus_automatico)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ row.estatus_automatico }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('prestamos.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link v-if="row.estatus_automatico !== 'devuelto'" :href="route('prestamos.devolver', row.id)" class="rounded border border-sky-500 px-2 py-1 text-xs text-sky-700 hover:bg-sky-50">Devolver</Link>
                    <Link v-if="row.estatus_automatico !== 'devuelto'" :href="route('prestamos.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button v-if="row.estatus_automatico !== 'devuelto'" @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
