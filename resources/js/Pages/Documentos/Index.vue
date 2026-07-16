<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';
import { confirmarEliminar } from '@/lib/swal';

const props = defineProps({
    documentos: Object,
    filtros: { type: Object, default: () => ({}) },
    tipos: { type: Array, default: () => [] },
    usuarios: { type: Array, default: () => [] },
});

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');

const filtros = reactive({
    q: props.filtros.q ?? '',
    numero: props.filtros.numero ?? '',
    tipo: props.filtros.tipo ?? '',
    fecha_desde: props.filtros.fecha_desde ?? '',
    fecha_hasta: props.filtros.fecha_hasta ?? '',
    paciente: props.filtros.paciente ?? '',
    subido_por: props.filtros.subido_por ?? '',
});

const buscar = () => {
    const activos = Object.fromEntries(Object.entries(filtros).filter(([, v]) => v !== '' && v !== null));
    router.get(route('documentos.index'), activos, { preserveState: true, preserveScroll: true, replace: true });
};

const limpiar = () => {
    Object.keys(filtros).forEach((k) => (filtros[k] = ''));
    router.get(route('documentos.index'), {}, { preserveState: true, preserveScroll: true, replace: true });
};

const eliminar = async (d) => {
    if (await confirmarEliminar('¿Eliminar este documento?')) router.delete(route('documentos.destroy', d.id));
};
</script>

<template>
    <AppLayout title="Documentos">
        <PageHeader title="Documentos">
            <template #actions>
                <Link :href="route('documentos.bulk.create')" class="rounded-md border border-emerald-600 px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">Carga múltiple</Link>
                <Link :href="route('documentos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Documento</Link>
            </template>
        </PageHeader>

        <!-- Buscador -->
        <Card title="Buscar documentos" class="mb-6">
            <form @submit.prevent="buscar" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Nombre del archivo</label>
                    <input v-model="filtros.q" type="text" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">N.º de documento</label>
                    <input v-model="filtros.numero" type="number" min="1" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Tipo de documento</label>
                    <select v-model="filtros.tipo" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        <option v-for="t in tipos" :key="t" :value="t">{{ t.toUpperCase() }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Paciente / Expediente</label>
                    <input v-model="filtros.paciente" type="text" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Fecha desde</label>
                    <input v-model="filtros.fecha_desde" type="date" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Fecha hasta</label>
                    <input v-model="filtros.fecha_hasta" type="date" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Cargado por</label>
                    <select v-model="filtros.subido_por" class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Todos</option>
                        <option v-for="u in usuarios" :key="u.id" :value="u.id">{{ u.nombre_usuario }}</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Buscar</button>
                    <button type="button" @click="limpiar" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpiar</button>
                </div>
            </form>
        </Card>

        <Card title="Documentos anexos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">N.º</th>
                            <th class="px-3 py-2 font-medium">Documento</th>
                            <th class="px-3 py-2 font-medium">Expediente</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                            <th class="px-3 py-2 font-medium">Fecha anexo</th>
                            <th class="px-3 py-2 font-medium">Cargado por</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in documentos.data" :key="d.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-gray-500">{{ d.id }}</td>
                            <td class="px-3 py-2 font-semibold">{{ d.nombre_documento }}</td>
                            <td class="px-3 py-2">{{ d.expediente ? d.expediente.codigo : '—' }}</td>
                            <td class="px-3 py-2 uppercase">{{ d.tipo_archivo }}</td>
                            <td class="px-3 py-2">{{ fecha(d.fecha_anexo) }}</td>
                            <td class="px-3 py-2">{{ d.cargado_por ? d.cargado_por.nombre_usuario : '—' }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <a :href="`/storage/${d.ruta_archivo}`" target="_blank" class="rounded border border-sky-500 px-2 py-1 text-xs text-sky-700 hover:bg-sky-50">Abrir</a>
                                    <Link :href="route('documentos.show', d.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('documentos.edit', d.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(d)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="documentos.data.length === 0"><td colspan="7" class="px-3 py-6 text-center text-gray-400">No hay documentos que coincidan con la búsqueda.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="documentos.links" />
        </Card>
    </AppLayout>
</template>
