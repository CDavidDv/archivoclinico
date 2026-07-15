<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

defineProps({ usuarios: Array });

const page = usePage();
const miId = computed(() => page.props.auth?.user?.id);

const eliminar = (u) => confirm('¿Eliminar este usuario?') && router.delete(route('usuarios.destroy', u.id));
</script>

<template>
    <AppLayout title="Usuarios">
        <PageHeader title="Usuarios">
            <template #actions>
                <Link :href="route('usuarios.create')" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">+ Nuevo Usuario</Link>
            </template>
        </PageHeader>

        <Card title="Gestión de Usuarios">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Usuario</th>
                            <th class="px-3 py-2 font-medium">Correo</th>
                            <th class="px-3 py-2 font-medium">Teléfono</th>
                            <th class="px-3 py-2 font-medium">Rol</th>
                            <th class="px-3 py-2 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="u in usuarios" :key="u.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 font-semibold">{{ u.nombre_usuario }}</td>
                            <td class="px-3 py-2">{{ u.email }}</td>
                            <td class="px-3 py-2">{{ u.telefono || '—' }}</td>
                            <td class="px-3 py-2 capitalize">{{ u.rol }}</td>
                            <td class="px-3 py-2">
                                <div class="flex justify-end gap-1">
                                    <Link :href="route('usuarios.show', u.id)" class="rounded border border-emerald-500 px-2 py-1 text-xs text-emerald-700 hover:bg-emerald-50">Ver</Link>
                                    <Link :href="route('usuarios.edit', u.id)" class="rounded border border-amber-500 px-2 py-1 text-xs text-amber-700 hover:bg-amber-50">Editar</Link>
                                    <button v-if="u.id !== miId" @click="eliminar(u)" class="rounded border border-red-500 px-2 py-1 text-xs text-red-700 hover:bg-red-50">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="usuarios.length === 0"><td colspan="5" class="px-3 py-6 text-center text-gray-400">No hay usuarios.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
