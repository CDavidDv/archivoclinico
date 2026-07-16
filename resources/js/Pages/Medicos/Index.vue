<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ medicos: Object, filtros: { type: Object, default: () => ({}) } });

const columns = [
    { key: 'numero_empleado', label: '# Empleado', filter: 'text' },
    { key: 'nombre', label: 'Nombre', filter: 'text' },
    { key: 'rfc', label: 'RFC', filter: 'text' },
    { key: 'cargo', label: 'Cargo', filter: 'text' },
    { key: 'area', label: 'Área', filter: 'text' },
    { key: 'tipo', label: 'Denominación', filter: 'text' },
];

const eliminar = async (m) => {
    if (await confirmarEliminar('¿Eliminar este médico?')) router.delete(route('medicos.destroy', m.id));
};
</script>

<template>
    <AppLayout title="Médicos">
        <PageHeader title="Médicos">
            <template #actions>
                <Link :href="route('medicos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Médico</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Médicos">
            <DataTable :columns="columns" :paginator="medicos" route-name="medicos.index" :filters="filtros" has-actions empty="No hay médicos registrados.">
                <template #col-numero_empleado="{ row }"><span class="font-semibold">{{ row.numero_empleado }}</span></template>
                <template #col-nombre="{ row }">{{ row.nombre }} {{ row.apellido_paterno }} {{ row.apellido_materno }}</template>
                <template #col-tipo="{ row }"><span class="capitalize">{{ row.tipo }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('medicos.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('medicos.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
