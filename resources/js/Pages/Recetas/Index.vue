<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';

defineProps({ recetas: Object, filtros: { type: Object, default: () => ({}) } });

const page = usePage();
const roles = computed(() => page.props.auth?.user?.roles ?? []);
const esMedico = computed(() => roles.value.includes('medico') || roles.value.includes('administrador'));

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    parcial: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    cancelada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');

const columns = [
    { key: 'folio', label: 'Folio', filter: 'text' },
    { key: 'derechohabiente', label: 'Derechohabiente', filter: 'text' },
    { key: 'medico', label: 'Médico', filter: 'text' },
    { key: 'fecha_receta', label: 'Fecha', filter: 'date' },
    { key: 'detalles_count', label: 'Ítems', filter: false },
    { key: 'estatus', label: 'Estatus', filter: 'select', options: ['pendiente', 'parcial', 'surtida', 'cancelada'].map((e) => ({ value: e, label: e })) },
];
</script>

<template>
    <AppLayout title="Recetas">
        <PageHeader title="Recetas">
            <template #actions>
                <Link v-if="esMedico" :href="route('recetas.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Receta</Link>
            </template>
        </PageHeader>

        <Card title="Cola de Recetas">
            <DataTable :columns="columns" :paginator="recetas" route-name="recetas.index" :filters="filtros" has-actions empty="No hay recetas.">
                <template #col-folio="{ row }"><span class="font-semibold">{{ row.folio }}</span></template>
                <template #col-derechohabiente="{ row }">{{ nom(row.derecho_habiente) }}</template>
                <template #col-medico="{ row }">{{ nom(row.medico) }}</template>
                <template #col-fecha_receta="{ row }">{{ fecha(row.fecha_receta) }}</template>
                <template #col-estatus="{ row }"><span :class="badge(row.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ row.estatus }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('recetas.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
