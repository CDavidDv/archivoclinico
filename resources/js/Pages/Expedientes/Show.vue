<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ expediente: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Detalle de Expediente">
        <PageHeader :title="expediente.codigo">
            <template #actions>
                <Link :href="route('expedientes.edit', expediente.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('expedientes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Expediente" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Código</dt><dd class="font-medium">{{ expediente.codigo }}</dd></div>
                <div><dt class="text-gray-500">Localización</dt><dd class="font-medium">{{ expediente.localizacion }}</dd></div>
                <div><dt class="text-gray-500">Tipo</dt><dd class="font-medium capitalize">{{ expediente.tipo }}</dd></div>
                <div><dt class="text-gray-500">Fecha de creación</dt><dd class="font-medium">{{ fecha(expediente.fecha_creacion) }}</dd></div>
                <div class="sm:col-span-2">
                    <dt class="text-gray-500">Derechohabiente</dt>
                    <dd class="font-medium">{{ expediente.derecho_habiente ? `${expediente.derecho_habiente.nombre} ${expediente.derecho_habiente.apellido_paterno} ${expediente.derecho_habiente.apellido_materno}` : '—' }}</dd>
                </div>
            </dl>
        </Card>

        <Card title="Documentos">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Nombre</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                            <th class="px-3 py-2 font-medium">Fecha anexo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="d in expediente.documentos" :key="d.id">
                            <td class="px-3 py-2">
                                <Link :href="route('documentos.show', d.id)" class="text-emerald-700 hover:underline">{{ d.nombre_documento }}</Link>
                            </td>
                            <td class="px-3 py-2 uppercase">{{ d.tipo_archivo }}</td>
                            <td class="px-3 py-2">{{ fecha(d.fecha_anexo) }}</td>
                        </tr>
                        <tr v-if="!expediente.documentos || expediente.documentos.length === 0"><td colspan="3" class="px-3 py-6 text-center text-gray-400">Sin documentos.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
