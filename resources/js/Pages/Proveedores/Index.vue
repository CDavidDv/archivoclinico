<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ proveedores: Object, filtros: { type: Object, default: () => ({}) } });

const columns = [
    { key: 'nombre', label: 'Nombre', filter: 'text' },
    { key: 'rfc', label: 'RFC', filter: 'text' },
    { key: 'telefono', label: 'Teléfono', filter: 'text' },
    { key: 'email', label: 'Email', filter: 'text' },
    { key: 'activo', label: 'Estado', filter: 'select', options: [{ value: '1', label: 'Activo' }, { value: '0', label: 'Inactivo' }] },
];

const eliminar = async (p) => {
    if (await confirmarEliminar('¿Eliminar este proveedor?')) router.delete(route('proveedores.destroy', p.id));
};
</script>

<template>
    <AppLayout title="Proveedores">
        <PageHeader title="Proveedores">
            <template #actions>
                <Link :href="route('proveedores.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">+ Nuevo Proveedor</Link>
            </template>
        </PageHeader>

        <Card title="Listado de Proveedores">
            <DataTable :columns="columns" :paginator="proveedores" route-name="proveedores.index" :filters="filtros" has-actions empty="No hay proveedores registrados.">
                <template #col-nombre="{ row }"><span class="font-semibold text-gray-800 dark:text-gray-100">{{ row.nombre }}</span></template>
                <template #col-rfc="{ row }">{{ row.rfc ?? '—' }}</template>
                <template #col-telefono="{ row }">{{ row.telefono ?? '—' }}</template>
                <template #col-email="{ row }">{{ row.email ?? '—' }}</template>
                <template #col-activo="{ row }">
                    <span :class="row.activo ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium">{{ row.activo ? 'Activo' : 'Inactivo' }}</span>
                </template>
                <template #actions="{ row }">
                    <Link :href="route('proveedores.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('proveedores.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
