<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';
import { confirmarEliminar } from '@/lib/swal';

defineProps({ documentos: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const eliminar = async (d) => {
    if (await confirmarEliminar('¿Eliminar este documento?')) router.delete(route('documentos.destroy', d.id));
};
</script>

<template>
    <AppLayout title="Documentos">
        <PageHeader title="Documentos">
            <template #actions>
                <Link :href="route('documentos.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Documento</Link>
            </template>
        </PageHeader>

        <Card title="Documentos anexos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Documento</th>
                            <th class="px-3 py-2 font-medium">Expediente</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                            <th class="px-3 py-2 font-medium">Fecha anexo</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in documentos.data" :key="d.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ d.nombre_documento }}</td>
                            <td class="px-3 py-2">{{ d.expediente ? d.expediente.codigo : '—' }}</td>
                            <td class="px-3 py-2 uppercase">{{ d.tipo_archivo }}</td>
                            <td class="px-3 py-2">{{ fecha(d.fecha_anexo) }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <a :href="`/storage/${d.ruta_archivo}`" target="_blank" class="rounded border border-sky-500 px-2 py-1 text-xs text-sky-700 hover:bg-sky-50">Abrir</a>
                                    <Link :href="route('documentos.show', d.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('documentos.edit', d.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button @click="eliminar(d)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="documentos.data.length === 0"><td colspan="5" class="px-3 py-6 text-center text-gray-400">No hay documentos registrados.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="documentos.links" />
        </Card>
    </AppLayout>
</template>
