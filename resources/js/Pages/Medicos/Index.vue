<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ medicos: Array });

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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium"># Empleado</th>
                            <th class="px-3 py-2 font-medium">Nombre</th>
                            <th class="px-3 py-2 font-medium">RFC</th>
                            <th class="px-3 py-2 font-medium">Cargo</th>
                            <th class="px-3 py-2 font-medium">Área</th>
                            <th class="px-3 py-2 font-medium">Denominación</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="m in medicos" :key="m.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ m.numero_empleado }}</td>
                            <td class="px-3 py-2">{{ m.nombre }} {{ m.apellido_paterno }} {{ m.apellido_materno }}</td>
                            <td class="px-3 py-2">{{ m.rfc }}</td>
                            <td class="px-3 py-2">{{ m.cargo }}</td>
                            <td class="px-3 py-2">{{ m.area }}</td>
                            <td class="px-3 py-2 capitalize">{{ m.tipo }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('medicos.show', m.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('medicos.edit', m.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(m)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="medicos.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay médicos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
