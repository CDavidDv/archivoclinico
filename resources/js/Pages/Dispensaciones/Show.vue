<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ dispensacion: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
</script>

<template>
    <AppLayout title="Detalle de Dispensación">
        <PageHeader :title="`Dispensación #${dispensacion.id}`">
            <template #actions>
                <Link v-if="dispensacion.receta" :href="route('recetas.show', dispensacion.receta.id)" class="rounded-md border border-emerald-500 px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-50">Ver receta</Link>
                <Link :href="route('dispensaciones.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos de la Dispensación" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Receta</dt><dd class="font-medium">{{ dispensacion.receta ? dispensacion.receta.folio : '—' }}</dd></div>
                <div><dt class="text-gray-500">Derechohabiente</dt><dd class="font-medium">{{ dispensacion.receta && dispensacion.receta.derecho_habiente ? nom(dispensacion.receta.derecho_habiente) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Fecha</dt><dd class="font-medium">{{ fecha(dispensacion.fecha) }}</dd></div>
                <div><dt class="text-gray-500">Usuario</dt><dd class="font-medium">{{ dispensacion.usuario ? dispensacion.usuario.nombre_usuario : '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Observaciones</dt><dd class="font-medium whitespace-pre-line">{{ dispensacion.observaciones || '—' }}</dd></div>
            </dl>
        </Card>

        <Card title="Medicamentos entregados">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Medicamento</th>
                            <th class="px-3 py-2 font-medium">Lote</th>
                            <th class="px-3 py-2 font-medium">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in dispensacion.detalles" :key="d.id">
                            <td class="px-3 py-2">{{ d.detalle_receta && d.detalle_receta.medicamento ? d.detalle_receta.medicamento.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ d.lote ? d.lote.numero_lote : '—' }}</td>
                            <td class="px-3 py-2">{{ d.cantidad }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
