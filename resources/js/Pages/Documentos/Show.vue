<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ documento: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const kb = (n) => (n ? `${(n / 1024).toFixed(1)} KB` : '—');

const url = computed(() => `/storage/${props.documento.ruta_archivo}`);
const ext = computed(() => (props.documento.tipo_archivo || '').toLowerCase());

const tipo = computed(() => {
    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext.value)) return 'imagen';
    if (ext.value === 'pdf') return 'pdf';
    if (['mp4', 'webm', 'mov', 'ogg'].includes(ext.value)) return 'video';
    if (['mp3', 'wav', 'oga'].includes(ext.value)) return 'audio';
    if (['txt', 'csv'].includes(ext.value)) return 'texto';
    return 'otro';
});
</script>

<template>
    <AppLayout title="Detalle de Documento">
        <PageHeader :title="documento.nombre_documento">
            <template #actions>
                <a :href="url" target="_blank" class="rounded-md bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-700">Abrir en pestaña</a>
                <a :href="url" :download="documento.nombre_original || documento.nombre_documento" class="rounded-md border border-sky-500 px-4 py-2 text-sm text-sky-700 hover:bg-sky-50">Descargar</a>
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

        <Card title="Vista previa">
            <img v-if="tipo === 'imagen'" :src="url" class="mx-auto max-h-[80vh] rounded border" />

            <iframe v-else-if="tipo === 'pdf' || tipo === 'texto'" :src="url" class="h-[80vh] w-full rounded border" />

            <video v-else-if="tipo === 'video'" :src="url" controls class="mx-auto max-h-[80vh] w-full rounded border" />

            <audio v-else-if="tipo === 'audio'" :src="url" controls class="w-full" />

            <div v-else class="flex flex-col items-center justify-center gap-3 py-12 text-center">
                <p class="text-sm text-gray-500">Este tipo de archivo ({{ documento.tipo_archivo }}) no admite vista previa en el navegador.</p>
                <a :href="url" target="_blank" class="rounded-md bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-700">Abrir / descargar archivo</a>
            </div>
        </Card>
    </AppLayout>
</template>
