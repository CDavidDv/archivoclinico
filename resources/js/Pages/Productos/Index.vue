<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ productos: Object, filtros: { type: Object, default: () => ({}) }, orden: { type: Object, default: () => ({}) } });

const cap = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : '');
const columns = [
    { key: 'clave', label: 'Clave', filter: 'text' },
    { key: 'nombre', label: 'Nombre', filter: 'text' },
    { key: 'categoria', label: 'Categoría', filter: 'text' },
    { key: 'unidad_medida', label: 'Unidad', filter: 'text' },
    { key: 'stock_total', label: 'Stock', filter: false, sortable: true, sortKey: 'stock' },
    { key: 'stock_minimo', label: 'Mínimo', filter: false, sortable: true, sortKey: 'stock_minimo' },
];

const eliminar = async (p) => {
    if (await confirmarEliminar('¿Eliminar este producto?')) router.delete(route('productos.destroy', p.id));
};
</script>

<template>
    <AppLayout title="Productos">
        <PageHeader title="Productos">
            <template #actions>
                <Link :href="route('productos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Producto</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Productos">
            <DataTable :columns="columns" :paginator="productos" route-name="productos.index" :filters="filtros" :orden="orden" has-actions empty="No hay productos registrados.">
                <template #col-clave="{ row }"><span class="font-semibold">{{ row.clave }}</span></template>
                <template #col-categoria="{ row }"><span class="rounded-full bg-gray-100 text-gray-600 px-2 py-0.5 text-xs">{{ cap(row.categoria) }}</span></template>
                <template #col-stock_total="{ row }">
                    <span :class="(row.stock_total ?? 0) <= row.stock_minimo ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800'" class="rounded-full px-2 py-0.5 text-xs font-medium">{{ row.stock_total ?? 0 }}</span>
                </template>
                <template #actions="{ row }">
                    <Link :href="route('productos.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('productos.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
