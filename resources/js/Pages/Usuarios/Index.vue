<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import DataTable from '@/Components/DataTable.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ usuarios: Object, filtros: { type: Object, default: () => ({}) } });

const page = usePage();
const miId = computed(() => page.props.auth?.user?.id);

const columns = [
    { key: 'nombre_usuario', label: 'Usuario', filter: 'text' },
    { key: 'email', label: 'Correo', filter: 'text' },
    { key: 'telefono', label: 'Teléfono', filter: 'text' },
    { key: 'rol', label: 'Rol', filter: 'select', options: ['medico', 'archivo', 'administrador', 'farmacia', 'almacen'].map((r) => ({ value: r, label: r })) },
];

const eliminar = async (u) => {
    if (await confirmarEliminar('¿Eliminar este usuario?')) router.delete(route('usuarios.destroy', u.id));
};
</script>

<template>
    <AppLayout title="Usuarios">
        <PageHeader title="Usuarios">
            <template #actions>
                <Link :href="route('usuarios.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Usuario</Link>
            </template>
        </PageHeader>

        <Card title="Gestión de Usuarios">
            <DataTable :columns="columns" :paginator="usuarios" route-name="usuarios.index" :filters="filtros" has-actions empty="No hay usuarios.">
                <template #col-nombre_usuario="{ row }"><span class="font-semibold">{{ row.nombre_usuario }}</span></template>
                <template #col-telefono="{ row }">{{ row.telefono || '—' }}</template>
                <template #col-rol="{ row }"><span class="capitalize">{{ row.rol }}</span></template>
                <template #actions="{ row }">
                    <Link :href="route('usuarios.show', row.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                    <Link :href="route('usuarios.edit', row.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                    <button v-if="row.id !== miId" @click="eliminar(row)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                </template>
            </DataTable>
        </Card>
    </AppLayout>
</template>
