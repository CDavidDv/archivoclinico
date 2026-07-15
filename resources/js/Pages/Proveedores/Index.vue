<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({
    proveedores: Array,
});

const eliminar = async (proveedor) => {
    if (await confirmarEliminar('¿Eliminar este proveedor?')) {
        router.delete(route('proveedores.destroy', proveedor.id));
    }
};
</script>

<template>
    <AppLayout title="Proveedores">
        <PageHeader title="Proveedores">
            <template #actions>
                <Link :href="route('proveedores.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">
                    + Nuevo Proveedor
                </Link>
            </template>
        </PageHeader>

        <Card title="Listado de Proveedores">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 dark:text-gray-400">
                            <th class="px-3 py-2 font-medium">Nombre</th>
                            <th class="px-3 py-2 font-medium">RFC</th>
                            <th class="px-3 py-2 font-medium">Teléfono</th>
                            <th class="px-3 py-2 font-medium">Email</th>
                            <th class="px-3 py-2 font-medium">Estado</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="p in proveedores" :key="p.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold text-gray-800 dark:text-gray-100">{{ p.nombre }}</td>
                            <td class="px-3 py-2">{{ p.rfc ?? '—' }}</td>
                            <td class="px-3 py-2">{{ p.telefono ?? '—' }}</td>
                            <td class="px-3 py-2">{{ p.email ?? '—' }}</td>
                            <td class="px-3 py-2">
                                <span :class="p.activo ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium">
                                    {{ p.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('proveedores.show', p.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('proveedores.edit', p.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(p)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="proveedores.length === 0">
                            <td colspan="6" class="px-3 py-6 text-center text-gray-400">No hay proveedores registrados.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
