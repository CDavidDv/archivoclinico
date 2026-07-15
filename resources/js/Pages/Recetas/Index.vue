<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({ recetas: Object, estatus: String });

const page = usePage();
const roles = computed(() => page.props.auth?.user?.roles ?? []);
const esMedico = computed(() => roles.value.includes('medico') || roles.value.includes('administrador'));

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const badge = (e) => ({
    pendiente: 'bg-amber-100 text-amber-800',
    parcial: 'bg-sky-100 text-sky-800',
    surtida: 'bg-emerald-100 text-emerald-800',
    cancelada: 'bg-red-100 text-red-700',
}[e] || 'bg-gray-100 text-gray-700');

const filtrar = (e) => router.get(route('recetas.index'), e ? { estatus: e } : {}, { preserveState: true });
const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
</script>

<template>
    <AppLayout title="Recetas">
        <PageHeader title="Recetas">
            <template #actions>
                <Link v-if="esMedico" :href="route('recetas.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nueva Receta</Link>
            </template>
        </PageHeader>

        <div class="mb-4 flex flex-wrap gap-1">
            <button @click="filtrar('')" :class="!estatus ? 'bg-emerald-600 text-white' : 'border border-gray-300 text-gray-700'" class="rounded px-3 py-1 text-sm">Todas</button>
            <button v-for="e in ['pendiente','parcial','surtida','cancelada']" :key="e" @click="filtrar(e)" :class="estatus === e ? 'bg-emerald-600 text-white' : 'border border-gray-300 text-gray-700'" class="rounded px-3 py-1 text-sm capitalize">{{ e }}</button>
        </div>

        <Card title="Cola de Recetas">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Folio</th>
                            <th class="px-3 py-2 font-medium">Derechohabiente</th>
                            <th class="px-3 py-2 font-medium">Médico</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Ítems</th>
                            <th class="px-3 py-2 font-medium">Estatus</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="r in recetas.data" :key="r.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ r.folio }}</td>
                            <td class="px-3 py-2">{{ nom(r.derecho_habiente) }}</td>
                            <td class="px-3 py-2">{{ nom(r.medico) }}</td>
                            <td class="px-3 py-2">{{ fecha(r.fecha_receta) }}</td>
                            <td class="px-3 py-2">{{ r.detalles_count }}</td>
                            <td class="px-3 py-2"><span :class="badge(r.estatus)" class="rounded-full px-2 py-0.5 text-xs font-medium capitalize">{{ r.estatus }}</span></td>
                            <td class="px-3 py-2 text-right">
                                <Link :href="route('recetas.show', r.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                            </td>
                        </tr>
                        <tr v-if="recetas.data.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay recetas.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="recetas.links" />
        </Card>
    </AppLayout>
</template>
