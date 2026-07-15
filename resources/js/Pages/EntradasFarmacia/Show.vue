<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ entrada: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Detalle de Entrada">
        <PageHeader :title="`Entrada #${entrada.id}`">
            <template #actions>
                <Link :href="route('entradas_farmacia.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos de la Entrada" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Fecha</dt><dd class="font-medium">{{ fecha(entrada.fecha) }}</dd></div>
                <div><dt class="text-gray-500">Usuario</dt><dd class="font-medium">{{ entrada.usuario ? entrada.usuario.nombre_usuario : '—' }}</dd></div>
                <div><dt class="text-gray-500">Origen</dt><dd class="font-medium">{{ entrada.transferencia ? `Transferencia #${entrada.transferencia.id}` : 'Manual' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Observaciones</dt><dd class="font-medium whitespace-pre-line">{{ entrada.observaciones || '—' }}</dd></div>
            </dl>
        </Card>

        <Card title="Lotes ingresados">
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
                        <tr v-for="d in entrada.detalles" :key="d.id">
                            <td class="px-3 py-2">{{ d.medicamento ? d.medicamento.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ d.lote ? d.lote.numero_lote : (d.numero_lote || '—') }}</td>
                            <td class="px-3 py-2">{{ fecha(d.lote ? d.lote.caducidad : d.caducidad) }}</td>
                            <td class="px-3 py-2">{{ d.cantidad }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
