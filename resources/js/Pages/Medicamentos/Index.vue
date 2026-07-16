<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ medicamentos: Object, filtros: { type: Object, default: () => ({}) } });

const columns = [
    { key: 'clave', label: 'Clave', filter: 'text' },
    { key: 'nombre', label: 'Nombre', filter: 'text' },
    { key: 'sustancia_activa', label: 'Sustancia activa', filter: 'text' },
    { key: 'presentacion', label: 'Presentación', filter: 'text' },
    { key: 'controlado', label: 'Controlado', filter: 'select', options: [{ value: '1', label: 'Sí' }, { value: '0', label: 'No' }] },
    { key: 'stock_total', label: 'Stock', filter: false },
    { key: 'stock_minimo', label: 'Mínimo', filter: false },
];

const eliminar = async (m) => {
    if (await confirmarEliminar('¿Eliminar este medicamento?')) router.delete(route('medicamentos.destroy', m.id));
};
</script>

<template>
    <AppLayout title="Medicamentos">
        <PageHeader title="Medicamentos">
            <template #actions>
                <Link :href="route('medicamentos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Medicamento</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Medicamentos">
            <DataTable :columns="columns" :paginator="medicamentos" route-name="medicamentos.index" :filters="filtros" has-actions empty="No hay medicamentos registrados.">
                <template #col-clave="{ row }"><span class="font-semibold">{{ row.clave }}</span></template>
                <template #col-controlado="{ row }">
                    <span v-if="row.controlado" class="rounded-full bg-purple-100 text-purple-700 px-2 py-0.5 text-xs font-medium">Sí</span>
                    <span v-else class="text-gray-400">—</span>
                </template>
                <template #col-stock_total="{ row }">
                    <span :class="(row.stock_total ?? 0) <= row.stock_minimo ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800'" class="rounded-full px-2 py-0.5 text-xs font-medium">{{ row.stock_total ?? 0 }}</span>
                </template>
                <template #actions="{ row }">
                    <Link :href="route('medicamentos.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('medicamentos.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
