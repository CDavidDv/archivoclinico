<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ prestamo: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno} ${p.apellido_materno ?? ''}`.trim() : '—');
</script>

<template>
    <AppLayout title="Detalle de Préstamo">
        <PageHeader :title="`Préstamo #${prestamo.id}`">
            <template #actions>
                <Link v-if="prestamo.estatus_automatico !== 'devuelto'" :href="route('prestamos.devolver', prestamo.id)" class="rounded-md bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-700">Devolver</Link>
                <Link :href="route('prestamos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Préstamo">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Expediente</dt><dd class="font-medium">{{ prestamo.expediente ? prestamo.expediente.codigo : '—' }}</dd></div>
                <div><dt class="text-gray-500">Derechohabiente</dt><dd class="font-medium">{{ prestamo.expediente && prestamo.expediente.derecho_habiente ? nom(prestamo.expediente.derecho_habiente) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Médico</dt><dd class="font-medium">{{ nom(prestamo.medico) }}</dd></div>
                <div><dt class="text-gray-500">Área destino</dt><dd class="font-medium">{{ prestamo.area_destino }}</dd></div>
                <div><dt class="text-gray-500">Entregado por</dt><dd class="font-medium">{{ nom(prestamo.entregado_por) }}</dd></div>
                <div><dt class="text-gray-500">Recibido por</dt><dd class="font-medium">{{ nom(prestamo.recibido_por) }}</dd></div>
                <div><dt class="text-gray-500">Fecha de salida</dt><dd class="font-medium">{{ fecha(prestamo.fecha_salida) }}</dd></div>
                <div><dt class="text-gray-500">Fecha de regreso</dt><dd class="font-medium">{{ fecha(prestamo.fecha_regreso) }}</dd></div>
                <div><dt class="text-gray-500">Devolución real</dt><dd class="font-medium">{{ fecha(prestamo.fecha_devolucion_real) }}</dd></div>
                <div><dt class="text-gray-500">Estatus</dt><dd class="font-medium capitalize">{{ prestamo.estatus_automatico }}</dd></div>
            </dl>
        </Card>
    </AppLayout>
</template>
