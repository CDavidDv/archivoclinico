<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ usuario: Object });
</script>

<template>
    <AppLayout title="Detalle de Usuario">
        <PageHeader :title="usuario.nombre_usuario">
            <template #actions>
                <Link :href="route('usuarios.edit', usuario.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('usuarios.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Datos del Usuario">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Nombre de usuario</dt><dd class="font-medium">{{ usuario.nombre_usuario }}</dd></div>
                <div><dt class="text-gray-500">Correo</dt><dd class="font-medium">{{ usuario.email }}</dd></div>
                <div><dt class="text-gray-500">Teléfono</dt><dd class="font-medium">{{ usuario.telefono || '—' }}</dd></div>
                <div><dt class="text-gray-500">Rol</dt><dd class="font-medium capitalize">{{ usuario.rol }}</dd></div>
            </dl>
        </Card>
    </AppLayout>
</template>
