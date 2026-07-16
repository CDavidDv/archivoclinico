<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ derechoHabientes: Object, filtros: { type: Object, default: () => ({}) } });

const columns = [
    { key: 'clave_generada', label: 'Clave', filter: 'text' },
    { key: 'nombre', label: 'Nombre', filter: 'text' },
    { key: 'rfc', label: 'RFC', filter: 'text' },
    { key: 'nss', label: 'NSS', filter: 'text' },
    { key: 'genero', label: 'Género', filter: 'text' },
];

const eliminar = async (d) => {
    if (await confirmarEliminar('¿Eliminar este derechohabiente?')) router.delete(route('derecho_habientes.destroy', d.id));
};
</script>

<template>
    <AppLayout title="Derechohabientes">
        <PageHeader title="Derechohabientes">
            <template #actions>
                <Link :href="route('derecho_habientes.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Derechohabiente</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Derechohabientes">
            <DataTable :columns="columns" :paginator="derechoHabientes" route-name="derecho_habientes.index" :filters="filtros" has-actions empty="No hay derechohabientes registrados.">
                <template #col-clave_generada="{ row }"><span class="font-semibold">{{ row.clave_generada }}</span></template>
                <template #col-nombre="{ row }">{{ row.nombre }} {{ row.apellido_paterno }} {{ row.apellido_materno }}</template>
                <template #actions="{ row }">
                    <Link :href="route('derecho_habientes.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('derecho_habientes.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
