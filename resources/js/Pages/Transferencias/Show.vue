<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ transferencia: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Detalle de Transferencia">
        <PageHeader :title="transferencia.folio">
            <template #actions>
                <Link :href="route('transferencias.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos de la Transferencia" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Folio</dt><dd class="font-medium">{{ transferencia.folio }}</dd></div>
                <div><dt class="text-gray-500">Fecha</dt><dd class="font-medium">{{ fecha(transferencia.fecha) }}</dd></div>
                <div><dt class="text-gray-500">Destino</dt><dd class="font-medium capitalize">{{ transferencia.destino }}{{ transferencia.area_destino ? ` — ${transferencia.area_destino}` : '' }}</dd></div>
                <div><dt class="text-gray-500">Solicitud</dt><dd class="font-medium">{{ transferencia.solicitud ? transferencia.solicitud.folio : '—' }}</dd></div>
                <div><dt class="text-gray-500">Usuario</dt><dd class="font-medium">{{ transferencia.usuario ? transferencia.usuario.nombre_usuario : '—' }}</dd></div>
                <div><dt class="text-gray-500">Entrada de farmacia</dt><dd class="font-medium">{{ transferencia.entrada_farmacia ? `#${transferencia.entrada_farmacia.id}` : '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Observaciones</dt><dd class="font-medium whitespace-pre-line">{{ transferencia.observaciones || '—' }}</dd></div>
            </dl>
        </Card>

        <Card title="Productos transferidos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Producto</th>
                            <th class="px-3 py-2 font-medium">Lote</th>
                            <th class="px-3 py-2 font-medium">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in transferencia.detalles" :key="d.id">
                            <td class="px-3 py-2">{{ d.producto ? d.producto.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ d.lote ? d.lote.numero_lote : '—' }}</td>
                            <td class="px-3 py-2">{{ d.cantidad }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
