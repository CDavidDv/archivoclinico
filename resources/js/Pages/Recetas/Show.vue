<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ receta: Object });

const page = usePage();
const roles = computed(() => page.props.auth?.user?.roles ?? []);
const esMedico = computed(() => roles.value.includes('medico') || roles.value.includes('administrador'));
const esFarmacia = computed(() => roles.value.includes('farmacia') || roles.value.includes('administrador'));

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno} ${p.apellido_materno ?? ''}`.trim() : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    parcial: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    cancelada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');

const puedeCancelar = computed(() => props.receta.estatus === 'pendiente');
const puedeDispensar = computed(() => ['pendiente', 'parcial'].includes(props.receta.estatus));

const cancelar = () => confirm('¿Cancelar esta receta?') && router.put(route('recetas.cancelar', props.receta.id));
</script>

<template>
    <AppLayout title="Detalle de Receta">
        <PageHeader :title="receta.folio">
            <template #actions>
                <Link v-if="esFarmacia && puedeDispensar" :href="route('dispensaciones.create', receta.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Dispensar</Link>
                <button v-if="esMedico && puedeCancelar" @click="cancelar" class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Cancelar receta</button>
                <Link :href="route('recetas.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos de la Receta" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Folio</dt><dd class="font-medium">{{ receta.folio }}</dd></div>
                <div><dt class="text-gray-500">Estatus</dt><dd><span :class="badge(receta.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ receta.estatus }}</span></dd></div>
                <div><dt class="text-gray-500">Derechohabiente</dt><dd class="font-medium">{{ nom(receta.derecho_habiente) }}</dd></div>
                <div><dt class="text-gray-500">Médico</dt><dd class="font-medium">{{ nom(receta.medico) }}</dd></div>
                <div><dt class="text-gray-500">Fecha</dt><dd class="font-medium">{{ fecha(receta.fecha_receta) }}</dd></div>
                <div><dt class="text-gray-500">Diagnóstico</dt><dd class="font-medium">{{ receta.diagnostico || '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Indicaciones</dt><dd class="font-medium whitespace-pre-line">{{ receta.indicaciones || '—' }}</dd></div>
            </dl>
        </Card>

        <Card title="Medicamentos prescritos" class="mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Medicamento</th>
                            <th class="px-3 py-2 font-medium">Prescrito</th>
                            <th class="px-3 py-2 font-medium">Surtido</th>
                            <th class="px-3 py-2 font-medium">Dosis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in receta.detalles" :key="d.id">
                            <td class="px-3 py-2">{{ d.medicamento ? d.medicamento.clave : '—' }}</td>
                            <td class="px-3 py-2">{{ d.medicamento ? d.medicamento.nombre : '—' }}</td>
                            <td class="px-3 py-2">{{ d.cantidad_prescrita }}</td>
                            <td class="px-3 py-2">{{ d.cantidad_surtida ?? 0 }}</td>
                            <td class="px-3 py-2">{{ d.dosis || '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <Card title="Dispensaciones">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">ID</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Usuario</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="disp in receta.dispensaciones" :key="disp.id">
                            <td class="px-3 py-2"><Link :href="route('dispensaciones.show', disp.id)" class="text-emerald-700 hover:underline">#{{ disp.id }}</Link></td>
                            <td class="px-3 py-2">{{ fecha(disp.fecha) }}</td>
                            <td class="px-3 py-2">{{ disp.usuario ? disp.usuario.nombre_usuario : '—' }}</td>
                        </tr>
                        <tr v-if="!receta.dispensaciones || receta.dispensaciones.length === 0"><td colspan="3" class="px-3 py-6 text-center text-gray-400">Sin dispensaciones.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
