<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ derechoHabientes: Array });

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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Nombre</th>
                            <th class="px-3 py-2 font-medium">RFC</th>
                            <th class="px-3 py-2 font-medium">NSS</th>
                            <th class="px-3 py-2 font-medium">Género</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in derechoHabientes" :key="d.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ d.clave_generada }}</td>
                            <td class="px-3 py-2">{{ d.nombre }} {{ d.apellido_paterno }} {{ d.apellido_materno }}</td>
                            <td class="px-3 py-2">{{ d.rfc }}</td>
                            <td class="px-3 py-2">{{ d.nss }}</td>
                            <td class="px-3 py-2">{{ d.genero }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('derecho_habientes.show', d.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('derecho_habientes.edit', d.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(d)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="derechoHabientes.length === 0"><td colspan="6" class="px-3 py-6 text-center text-gray-400">No hay derechohabientes registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
