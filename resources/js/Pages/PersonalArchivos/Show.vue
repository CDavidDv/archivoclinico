<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ personalArchivo: Object });
</script>

<template>
    <AppLayout title="Detalle de Personal">
        <PageHeader :title="`${personalArchivo.nombre} ${personalArchivo.apellido_paterno} ${personalArchivo.apellido_materno}`">
            <template #actions>
                <Link :href="route('personal_archivos.edit', personalArchivo.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('personal_archivos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Personal">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Número de empleado</dt><dd class="font-medium">{{ personalArchivo.numero_empleado }}</dd></div>
                <div><dt class="text-gray-500">RFC</dt><dd class="font-medium">{{ personalArchivo.rfc }}</dd></div>
                <div><dt class="text-gray-500">Cargo</dt><dd class="font-medium">{{ personalArchivo.cargo }}</dd></div>
                <div><dt class="text-gray-500">Área</dt><dd class="font-medium">{{ personalArchivo.area }}</dd></div>
                <div><dt class="text-gray-500">Tipo</dt><dd class="font-medium capitalize">{{ personalArchivo.tipo }}</dd></div>
            </dl>
        </Card>
    </AppLayout>
</template>
