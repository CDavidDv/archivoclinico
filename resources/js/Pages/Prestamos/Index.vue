<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ prestamos: Array });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    devuelto: 'bg-emerald-100 text-emerald-800',
    vencido: 'bg-red-100 text-red-700',
    pendiente: 'bg-amber-100 text-amber-800',
}[e] || 'bg-gray-100 text-gray-700');

const eliminar = (p) => confirm('¿Eliminar este préstamo?') && router.delete(route('prestamos.destroy', p.id));
</script>

<template>
    <AppLayout title="Préstamos">
        <PageHeader title="Préstamos de Expedientes">
            <template #actions>
                <Link :href="route('prestamos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Préstamo</Link>
            </template>
        </PageHeader>

        <Card title="Listado de Préstamos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Expediente</th>
                            <th class="px-3 py-2 font-medium">Médico</th>
                            <th class="px-3 py-2 font-medium">Área</th>
                            <th class="px-3 py-2 font-medium">Salida</th>
                            <th class="px-3 py-2 font-medium">Regreso</th>
                            <th class="px-3 py-2 font-medium">Estatus</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="p in prestamos" :key="p.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ p.expediente ? p.expediente.codigo : '—' }}</td>
                            <td class="px-3 py-2">{{ p.medico ? `${p.medico.nombre} ${p.medico.apellido_paterno}` : '—' }}</td>
                            <td class="px-3 py-2">{{ p.area_destino }}</td>
                            <td class="px-3 py-2">{{ fecha(p.fecha_salida) }}</td>
                            <td class="px-3 py-2">{{ fecha(p.fecha_regreso) }}</td>
                            <td class="px-3 py-2">
                                <span :class="badge(p.estatus_automatico)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ p.estatus_automatico }}</span>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('prestamos.show', p.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link v-if="p.estatus_automatico !== 'devuelto'" :href="route('prestamos.devolver', p.id)" class="rounded border border-sky-500 px-2 py-1 text-xs text-sky-700 hover:bg-sky-50">Devolver</Link>
                                    <Link v-if="p.estatus_automatico !== 'devuelto'" :href="route('prestamos.edit', p.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button v-if="p.estatus_automatico !== 'devuelto'" @click="eliminar(p)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="prestamos.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay préstamos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
