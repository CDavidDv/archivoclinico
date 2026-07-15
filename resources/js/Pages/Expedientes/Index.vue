<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ expedientes: Array });

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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Código</th>
                            <th class="px-3 py-2 font-medium">Derechohabiente</th>
                            <th class="px-3 py-2 font-medium">Localización</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="e in expedientes" :key="e.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ e.codigo }}</td>
                            <td class="px-3 py-2">
                                <span v-if="e.derecho_habiente">{{ e.derecho_habiente.nombre }} {{ e.derecho_habiente.apellido_paterno }}</span>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                            <td class="px-3 py-2">{{ e.localizacion }}</td>
                            <td class="px-3 py-2 capitalize">{{ e.tipo }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('expedientes.show', e.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('expedientes.edit', e.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(e)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="expedientes.length === 0"><td colspan="5" class="px-3 py-6 text-center text-gray-400">No hay expedientes registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
