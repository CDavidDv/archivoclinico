<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ documento: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const kb = (n) => (n ? `${(n / 1024).toFixed(1)} KB` : '—');
const esImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes((props.documento.tipo_archivo || '').toLowerCase());
</script>

<template>
    <AppLayout title="Detalle de Documento">
        <PageHeader :title="documento.nombre_documento">
            <template #actions>
                <a :href="`/storage/${documento.ruta_archivo}`" target="_blank" class="rounded-md bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-700">Abrir archivo</a>
                <Link :href="route('documentos.edit', documento.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('documentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Documento" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Expediente</dt><dd class="font-medium">{{ documento.expediente ? documento.expediente.codigo : '—' }}</dd></div>
                <div><dt class="text-gray-500">Nombre original</dt><dd class="font-medium">{{ documento.nombre_original }}</dd></div>
                <div><dt class="text-gray-500">Tipo</dt><dd class="font-medium uppercase">{{ documento.tipo_archivo }}</dd></div>
                <div><dt class="text-gray-500">Tamaño</dt><dd class="font-medium">{{ kb(documento.tamano) }}</dd></div>
                <div><dt class="text-gray-500">Fecha de anexo</dt><dd class="font-medium">{{ fecha(documento.fecha_anexo) }}</dd></div>
            </dl>
        </Card>

        <Card v-if="esImagen" title="Vista previa">
            <img :src="`/storage/${documento.ruta_archivo}`" class="max-h-96 rounded border" />
        </Card>
    </AppLayout>
</template>
