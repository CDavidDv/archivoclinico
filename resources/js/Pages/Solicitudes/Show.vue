<script setup>
import { ref, computed } from 'vue';
import { Link, router, usePage, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

const props = defineProps({ solicitud: Object });

const page = usePage();
const roles = computed(() => page.props.auth?.user?.roles ?? []);
const puedeAtender = computed(() =>
    (roles.value.includes('almacen') || roles.value.includes('administrador')) &&
    props.solicitud.estatus === 'pendiente'
);

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    aprobada: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    rechazada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');

const aprobar = () => confirm('¿Aprobar esta solicitud?') && router.put(route('solicitudes.aprobar', props.solicitud.id));

const mostrarRechazo = ref(false);
const rechazoForm = useForm({ motivo_rechazo: '' });
const rechazar = () => rechazoForm.put(route('solicitudes.rechazar', props.solicitud.id));
</script>

<template>
    <AppLayout title="Detalle de Solicitud">
        <PageHeader :title="solicitud.folio">
            <template #actions>
                <template v-if="puedeAtender">
                    <button @click="aprobar" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Aprobar</button>
                    <button @click="mostrarRechazo = !mostrarRechazo" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Rechazar</button>
                </template>
                <Link :href="route('solicitudes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card v-if="mostrarRechazo && puedeAtender" title="Rechazar solicitud" class="mb-6">
            <form @submit.prevent="rechazar">
                <TextareaField v-model="rechazoForm.motivo_rechazo" label="Motivo del rechazo" :error="rechazoForm.errors.motivo_rechazo" required />
                <div class="mt-4">
                    <PrimaryButton :class="{ 'opacity-25': rechazoForm.processing }" :disabled="rechazoForm.processing">Confirmar rechazo</PrimaryButton>
                </div>
            </form>
        </Card>

        <Card title="Datos de la Solicitud" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Folio</dt><dd class="font-medium">{{ solicitud.folio }}</dd></div>
                <div><dt class="text-gray-500">Módulo solicitante</dt><dd class="font-medium capitalize">{{ solicitud.modulo_solicitante }}</dd></div>
                <div><dt class="text-gray-500">Solicita</dt><dd class="font-medium">{{ solicitud.usuario_solicita ? solicitud.usuario_solicita.nombre_usuario : '—' }}</dd></div>
                <div><dt class="text-gray-500">Atiende</dt><dd class="font-medium">{{ solicitud.usuario_atiende ? solicitud.usuario_atiende.nombre_usuario : '—' }}</dd></div>
                <div><dt class="text-gray-500">Fecha</dt><dd class="font-medium">{{ fecha(solicitud.fecha_solicitud) }}</dd></div>
                <div><dt class="text-gray-500">Estatus</dt><dd><span :class="badge(solicitud.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ solicitud.estatus }}</span></dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Observaciones</dt><dd class="font-medium whitespace-pre-line">{{ solicitud.observaciones || '—' }}</dd></div>
                <div v-if="solicitud.motivo_rechazo" class="sm:col-span-2"><dt class="text-gray-500">Motivo de rechazo</dt><dd class="font-medium text-red-700">{{ solicitud.motivo_rechazo }}</dd></div>
            </dl>
        </Card>

        <Card title="Productos solicitados">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Producto</th>
                            <th class="px-3 py-2 font-medium">Cantidad solicitada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in solicitud.detalles" :key="d.id">
                            <td class="px-3 py-2">{{ d.producto ? d.producto.clave : '—' }}</td>
                            <td class="px-3 py-2">{{ d.producto ? d.producto.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ d.cantidad_solicitada }}</td>
                        </tr>
                        <tr v-if="!solicitud.detalles || solicitud.detalles.length === 0"><td colspan="3" class="px-3 py-6 text-center text-gray-400">Sin productos.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
