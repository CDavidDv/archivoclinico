<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ bajoMinimo: Array, porCaducar: Array, caducados: Array });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Alertas de Farmacia">
        <PageHeader title="Alertas de Farmacia" />

        <Card title="Medicamentos bajo mínimo" class="mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Medicamento</th>
                            <th class="px-3 py-2 font-medium">Stock</th>
                            <th class="px-3 py-2 font-medium">Mínimo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="m in bajoMinimo" :key="m.id" class="bg-red-50/50">
                            <td class="px-3 py-2 font-semibold">{{ m.clave }}</td>
                            <td class="px-3 py-2">{{ m.nombre }}</td>
                            <td class="px-3 py-2"><span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">{{ m.stock_total ?? 0 }}</span></td>
                            <td class="px-3 py-2">{{ m.stock_minimo }}</td>
                        </tr>
                        <tr v-if="bajoMinimo.length === 0"><td colspan="4" class="px-3 py-6 text-center text-gray-400">Sin medicamentos bajo mínimo.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <Card title="Lotes por caducar (30 días)" class="mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Medicamento</th>
                            <th class="px-3 py-2 font-medium">Lote</th>
                            <th class="px-3 py-2 font-medium">Caducidad</th>
                            <th class="px-3 py-2 font-medium">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="l in porCaducar" :key="l.id" class="bg-amber-50/50">
                            <td class="px-3 py-2">{{ l.medicamento ? l.medicamento.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ l.numero_lote }}</td>
                            <td class="px-3 py-2">{{ fecha(l.caducidad) }}</td>
                            <td class="px-3 py-2">{{ l.cantidad_actual }}</td>
                        </tr>
                        <tr v-if="porCaducar.length === 0"><td colspan="4" class="px-3 py-6 text-center text-gray-400">Sin lotes por caducar.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <Card title="Lotes caducados">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Medicamento</th>
                            <th class="px-3 py-2 font-medium">Lote</th>
                            <th class="px-3 py-2 font-medium">Caducidad</th>
                            <th class="px-3 py-2 font-medium">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="l in caducados" :key="l.id" class="bg-red-50">
                            <td class="px-3 py-2">{{ l.medicamento ? l.medicamento.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ l.numero_lote }}</td>
                            <td class="px-3 py-2 text-red-700">{{ fecha(l.caducidad) }}</td>
                            <td class="px-3 py-2">{{ l.cantidad_actual }}</td>
                        </tr>
                        <tr v-if="caducados.length === 0"><td colspan="4" class="px-3 py-6 text-center text-gray-400">Sin lotes caducados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
