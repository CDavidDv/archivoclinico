<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ medicamentos: Array });

const eliminar = (m) => confirm('¿Eliminar este medicamento?') && router.delete(route('medicamentos.destroy', m.id));
</script>

<template>
    <AppLayout title="Medicamentos">
        <PageHeader title="Medicamentos">
            <template #actions>
                <Link :href="route('medicamentos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Medicamento</Link>
            </template>
        </PageHeader>

        <Card title="Catálogo de Medicamentos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Nombre</th>
                            <th class="px-3 py-2 font-medium">Sustancia activa</th>
                            <th class="px-3 py-2 font-medium">Presentación</th>
                            <th class="px-3 py-2 font-medium">Stock</th>
                            <th class="px-3 py-2 font-medium">Mínimo</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="m in medicamentos" :key="m.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ m.clave }}</td>
                            <td class="px-3 py-2">{{ m.nombre }}</td>
                            <td class="px-3 py-2">{{ m.sustancia_activa }}</td>
                            <td class="px-3 py-2">{{ m.presentacion }}</td>
                            <td class="px-3 py-2">
                                <span :class="(m.stock_total ?? 0) <= m.stock_minimo ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800'" class="rounded-full px-2 py-0.5 text-xs font-medium">{{ m.stock_total ?? 0 }}</span>
                            </td>
                            <td class="px-3 py-2">{{ m.stock_minimo }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('medicamentos.show', m.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('medicamentos.edit', m.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(m)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="medicamentos.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay medicamentos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
