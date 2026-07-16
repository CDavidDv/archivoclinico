<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ expedientes: Object, filtros: { type: Object, default: () => ({}) } });

const columns = [
    { key: 'codigo', label: 'Código', filter: 'text' },
    { key: 'derechohabiente', label: 'Derechohabiente', filter: 'text' },
    { key: 'localizacion', label: 'Localización', filter: 'text' },
    { key: 'tipo', label: 'Tipo', filter: 'text' },
];

const eliminar = async (e) => {
    if (await confirmarEliminar('¿Eliminar este expediente?')) router.delete(route('expedientes.destroy', e.id));
};
</script>

<template>
    <AppLayout title="Expedientes">
        <PageHeader title="Expedientes">
            <template #actions>
                <Link :href="route('expedientes.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Expediente</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Expedientes">
            <DataTable :columns="columns" :paginator="expedientes" route-name="expedientes.index" :filters="filtros" has-actions empty="No hay expedientes registrados.">
                <template #col-codigo="{ row }"><span class="font-semibold">{{ row.codigo }}</span></template>
                <template #col-derechohabiente="{ row }">
                    <span v-if="row.derecho_habiente">{{ row.derecho_habiente.nombre }} {{ row.derecho_habiente.apellido_paterno }}</span>
                    <span v-else class="text-gray-400">—</span>
                </template>
                <template #col-tipo="{ row }"><span class="capitalize">{{ row.tipo }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('expedientes.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('expedientes.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
