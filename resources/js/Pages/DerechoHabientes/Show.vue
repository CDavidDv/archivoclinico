<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ dh: Object });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
</script>

<template>
    <AppLayout title="Detalle de Derechohabiente">
        <PageHeader :title="`${dh.nombre} ${dh.apellido_paterno} ${dh.apellido_materno}`">
            <template #actions>
                <Link :href="route('derecho_habientes.edit', dh.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('derecho_habientes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Derechohabiente" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Clave generada</dt><dd class="font-medium">{{ dh.clave_generada }}</dd></div>
                <div><dt class="text-gray-500">RFC</dt><dd class="font-medium">{{ dh.rfc }}</dd></div>
                <div><dt class="text-gray-500">NSS</dt><dd class="font-medium">{{ dh.nss }}</dd></div>
                <div><dt class="text-gray-500">Clave de identificación</dt><dd class="font-medium">{{ dh.clave_identificacion }}</dd></div>
                <div><dt class="text-gray-500">Fecha de nacimiento</dt><dd class="font-medium">{{ fecha(dh.fecha_nacimiento) }}</dd></div>
                <div><dt class="text-gray-500">Género</dt><dd class="font-medium">{{ dh.genero }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Síntomas</dt><dd class="font-medium whitespace-pre-line">{{ dh.sintomas || '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">Tratamiento</dt><dd class="font-medium whitespace-pre-line">{{ dh.tratamiento || '—' }}</dd></div>
            </dl>
        </Card>
    </AppLayout>
</template>
