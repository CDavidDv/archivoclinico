<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ medico: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Detalle de Médico">
        <PageHeader :title="`${medico.nombre} ${medico.apellido_paterno} ${medico.apellido_materno}`">
            <template #actions>
                <Link :href="route('medicos.edit', medico.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('medicos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Médico" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Número de empleado</dt><dd class="font-medium">{{ medico.numero_empleado }}</dd></div>
                <div><dt class="text-gray-500">RFC</dt><dd class="font-medium">{{ medico.rfc }}</dd></div>
                <div><dt class="text-gray-500">Cargo</dt><dd class="font-medium">{{ medico.cargo }}</dd></div>
                <div><dt class="text-gray-500">Área</dt><dd class="font-medium">{{ medico.area }}</dd></div>
                <div><dt class="text-gray-500">Denominación</dt><dd class="font-medium capitalize">{{ medico.tipo }}</dd></div>
            </dl>
        </Card>

        <Card title="Préstamos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">ID</th>
                            <th class="px-3 py-2 font-medium">Expediente</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="p in medico.prestamos" :key="p.id">
                            <td class="px-3 py-2">{{ p.id }}</td>
                            <td class="px-3 py-2">{{ p.id_expediente }}</td>
                            <td class="px-3 py-2">{{ fecha(p.fecha_prestamo ?? p.created_at) }}</td>
                            <td class="px-3 py-2">{{ p.estado ?? '—' }}</td>
                        </tr>
                        <tr v-if="!medico.prestamos || medico.prestamos.length === 0"><td colspan="4" class="px-3 py-6 text-center text-gray-400">Sin préstamos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
