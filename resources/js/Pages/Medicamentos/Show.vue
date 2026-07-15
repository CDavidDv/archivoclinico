<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ medicamento: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const caducado = (l) => l.caducidad && new Date(l.caducidad) < new Date() && l.cantidad_actual > 0;
</script>

<template>
    <AppLayout title="Detalle de Medicamento">
        <PageHeader :title="`${medicamento.clave} — ${medicamento.nombre}`">
            <template #actions>
                <Link :href="route('medicamentos.edit', medicamento.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('medicamentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card :title="medicamento.nombre" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Sustancia activa</dt><dd class="font-medium">{{ medicamento.sustancia_activa }}</dd></div>
                <div><dt class="text-gray-500">Presentación</dt><dd class="font-medium">{{ medicamento.presentacion }}</dd></div>
                <div><dt class="text-gray-500">Unidad de medida</dt><dd class="font-medium">{{ medicamento.unidad_medida }}</dd></div>
                <div>
                    <dt class="text-gray-500">Stock total</dt>
                    <dd>
                        <span :class="medicamento.stock_total <= medicamento.stock_minimo ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800'" class="rounded-full px-2 py-0.5 text-xs font-medium">{{ medicamento.stock_total }}</span>
                        <span class="text-gray-500"> (mínimo: {{ medicamento.stock_minimo }})</span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Producto de almacén</dt>
                    <dd class="font-medium">{{ medicamento.producto ? `${medicamento.producto.clave} — ${medicamento.producto.nombre}` : 'Sin vínculo' }}</dd>
                </div>
            </dl>
        </Card>

        <Card title="Lotes">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Número de lote</th>
                            <th class="px-3 py-2 font-medium">Caducidad</th>
                            <th class="px-3 py-2 font-medium">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="l in medicamento.lotes" :key="l.id" :class="caducado(l) ? 'bg-red-50' : ''">
                            <td class="px-3 py-2">{{ l.numero_lote }}</td>
                            <td class="px-3 py-2">
                                {{ fecha(l.caducidad) }}
                                <span v-if="caducado(l)" class="ml-1 rounded-full bg-red-100 text-red-700 px-2 py-0.5 text-xs">Caducado</span>
                            </td>
                            <td class="px-3 py-2">{{ l.cantidad_actual }}</td>
                        </tr>
                        <tr v-if="!medicamento.lotes || medicamento.lotes.length === 0"><td colspan="3" class="px-3 py-6 text-center text-gray-400">Sin lotes registrados.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
