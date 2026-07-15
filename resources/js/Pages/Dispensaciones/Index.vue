<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({ dispensaciones: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
</script>

<template>
    <AppLayout title="Dispensaciones">
        <PageHeader title="Dispensaciones" />

        <Card title="Historial de Dispensaciones">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">ID</th>
                            <th class="px-3 py-2 font-medium">Receta</th>
                            <th class="px-3 py-2 font-medium">Derechohabiente</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Usuario</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in dispensaciones.data" :key="d.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">#{{ d.id }}</td>
                            <td class="px-3 py-2">{{ d.receta ? d.receta.folio : '—' }}</td>
                            <td class="px-3 py-2">{{ d.receta && d.receta.derecho_habiente ? nom(d.receta.derecho_habiente) : '—' }}</td>
                            <td class="px-3 py-2">{{ fecha(d.fecha) }}</td>
                            <td class="px-3 py-2">{{ d.usuario ? d.usuario.nombre_usuario : '—' }}</td>
                            <td class="px-3 py-2 text-right">
                                <Link :href="route('dispensaciones.show', d.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                            </td>
                        </tr>
                        <tr v-if="dispensaciones.data.length === 0"><td colspan="6" class="px-3 py-6 text-center text-gray-400">No hay dispensaciones.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="dispensaciones.links" />
        </Card>
    </AppLayout>
</template>
