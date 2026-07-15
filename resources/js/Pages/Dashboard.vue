<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const roles = computed(() => user.value?.roles ?? []);
const hasRole = (...r) => roles.value.some((x) => r.includes(x));
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">
                Bienvenido, {{ user?.nombre_usuario }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Sistema Integral de Gestión — Farmacia, Almacén y Archivo Clínico
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- ARCHIVO CLÍNICO -->
            <div v-if="hasRole('administrador', 'archivo', 'medico')" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-emerald-700 text-white px-4 py-3 font-semibold">Archivo Clínico</div>
                <div class="p-4 space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Expedientes, documentos y préstamos.</p>
                    <div class="grid gap-2">
                        <Link :href="route('expedientes.index')" class="btn-outline">Expedientes</Link>
                        <Link :href="route('documentos.index')" class="btn-outline">Documentos</Link>
                        <Link v-if="hasRole('administrador', 'medico')" :href="route('recetas.index')" class="btn-outline">Recetas</Link>
                        <Link v-if="hasRole('administrador', 'archivo')" :href="route('prestamos.index')" class="btn-outline">Préstamos</Link>
                    </div>
                </div>
            </div>

            <!-- FARMACIA -->
            <div v-if="hasRole('administrador', 'farmacia')" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-emerald-700 text-white px-4 py-3 font-semibold">Farmacia</div>
                <div class="p-4 space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Medicamentos, recetas y dispensación.</p>
                    <div class="grid gap-2">
                        <Link :href="route('medicamentos.index')" class="btn-outline">Medicamentos</Link>
                        <Link :href="route('recetas.index', { estatus: 'pendiente' })" class="btn-outline">Recetas Pendientes</Link>
                        <Link :href="route('farmacia.alertas')" class="btn-outline-danger">Alertas</Link>
                    </div>
                </div>
            </div>

            <!-- ALMACÉN -->
            <div v-if="hasRole('administrador', 'almacen')" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="bg-emerald-700 text-white px-4 py-3 font-semibold">Almacén</div>
                <div class="p-4 space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Inventario, proveedores y transferencias.</p>
                    <div class="grid gap-2">
                        <Link :href="route('productos.index')" class="btn-outline">Productos</Link>
                        <Link :href="route('solicitudes.index')" class="btn-outline">Solicitudes</Link>
                        <Link :href="route('almacen.existencias')" class="btn-outline">Existencias</Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.btn-outline {
    @apply text-center text-sm px-3 py-2 rounded-md border border-emerald-600 text-emerald-700 hover:bg-emerald-600 hover:text-white transition dark:text-emerald-400;
}
.btn-outline-danger {
    @apply text-center text-sm px-3 py-2 rounded-md border border-red-500 text-red-600 hover:bg-red-500 hover:text-white transition;
}
</style>
