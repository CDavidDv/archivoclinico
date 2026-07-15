<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({
    proveedor: Object,
});

const fecha = (f) => f ? new Date(f).toLocaleDateString('es-MX') : '—';
const cap = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '—';
</script>

<template>
    <AppLayout title="Detalle de Proveedor">
        <PageHeader title="Detalle de Proveedor">
            <template #actions>
                <Link :href="route('proveedores.edit', proveedor.id)" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Editar</Link>
                <Link :href="route('proveedores.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card :title="proveedor.nombre" class="mb-6">
            <dl class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">RFC</dt><dd class="font-medium">{{ proveedor.rfc ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Teléfono</dt><dd class="font-medium">{{ proveedor.telefono ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ proveedor.email ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Dirección</dt><dd class="font-medium">{{ proveedor.direccion ?? '—' }}</dd></div>
                <div>
                    <dt class="text-gray-500">Estado</dt>
                    <dd>
                        <span :class="proveedor.activo ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'" class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium">
                            {{ proveedor.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </dd>
                </div>
            </dl>
        </Card>

        <Card title="Entradas Registradas">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Folio</th>
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="e in proveedor.entradas" :key="e.id">
                            <td class="px-3 py-2">
                                <Link :href="route('entradas_almacen.show', e.id)" class="text-emerald-700 hover:underline">#{{ e.id }}</Link>
                            </td>
                            <td class="px-3 py-2">{{ fecha(e.fecha) }}</td>
                            <td class="px-3 py-2">{{ cap(e.tipo) }}</td>
                        </tr>
                        <tr v-if="!proveedor.entradas || proveedor.entradas.length === 0">
                            <td colspan="3" class="px-3 py-6 text-center text-gray-400">Sin entradas registradas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
