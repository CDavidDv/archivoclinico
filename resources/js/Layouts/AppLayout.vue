<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import FlashMessages from '@/Components/FlashMessages.vue';

defineProps({
    title: String,
});

const page = usePage();
const showingNavigationDropdown = ref(false);

const user = computed(() => page.props.auth.user);
const roles = computed(() => user.value?.roles ?? []);

const hasRole = (...roand) => roles.value.some((r) => roand.includes(r));

const logout = () => router.post(route('logout'));
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">
            <nav class="bg-emerald-700 text-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <Link :href="route('dashboard')" class="font-semibold tracking-wide text-lg">
                                Sistema Integral
                            </Link>

                            <div class="hidden md:flex md:items-center md:ms-10 md:space-x-1">
                                <!-- ARCHIVO CLÍNICO -->
                                <Dropdown v-if="hasRole('administrador', 'archivo', 'medico')" align="left" width="56">
                                    <template #trigger>
                                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                            Archivo Clínico
                                            <svg class="ms-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('derecho_habientes.index')">Derechohabientes</DropdownLink>
                                        <DropdownLink :href="route('expedientes.index')">Expedientes</DropdownLink>
                                        <DropdownLink :href="route('documentos.index')">Documentos</DropdownLink>
                                        <template v-if="hasRole('administrador', 'archivo')">
                                            <DropdownLink :href="route('prestamos.index')">Préstamos</DropdownLink>
                                            <DropdownLink :href="route('medicos.index')">Médicos</DropdownLink>
                                            <DropdownLink :href="route('personal_archivos.index')">Personal Archivo</DropdownLink>
                                        </template>
                                        <template v-if="hasRole('administrador', 'medico')">
                                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                            <DropdownLink :href="route('recetas.index')">Recetas</DropdownLink>
                                        </template>
                                    </template>
                                </Dropdown>

                                <!-- FARMACIA -->
                                <Dropdown v-if="hasRole('administrador', 'farmacia')" align="left" width="56">
                                    <template #trigger>
                                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                            Farmacia
                                            <svg class="ms-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('medicamentos.index')">Medicamentos</DropdownLink>
                                        <DropdownLink :href="route('recetas.index')">Recetas</DropdownLink>
                                        <DropdownLink :href="route('dispensaciones.index')">Dispensaciones</DropdownLink>
                                        <DropdownLink :href="route('entradas_farmacia.index')">Entradas</DropdownLink>
                                        <DropdownLink :href="route('salidas_farmacia.index')">Salidas</DropdownLink>
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                        <DropdownLink :href="route('farmacia.alertas')">Alertas</DropdownLink>
                                    </template>
                                </Dropdown>

                                <!-- ALMACÉN -->
                                <Dropdown v-if="hasRole('administrador', 'almacen')" align="left" width="56">
                                    <template #trigger>
                                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                            Almacén
                                            <svg class="ms-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('productos.index')">Productos</DropdownLink>
                                        <DropdownLink :href="route('proveedores.index')">Proveedores</DropdownLink>
                                        <DropdownLink :href="route('entradas_almacen.index')">Entradas</DropdownLink>
                                        <DropdownLink :href="route('salidas_almacen.index')">Salidas</DropdownLink>
                                        <DropdownLink :href="route('transferencias.index')">Transferencias</DropdownLink>
                                        <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                        <DropdownLink :href="route('almacen.existencias')">Reporte de Existencias</DropdownLink>
                                    </template>
                                </Dropdown>

                                <!-- SOLICITUDES -->
                                <NavLink
                                    v-if="hasRole('administrador', 'archivo', 'farmacia', 'almacen')"
                                    :href="route('solicitudes.index')"
                                    :active="route().current('solicitudes.*')"
                                    class="!text-white"
                                >
                                    Solicitudes
                                </NavLink>

                                <!-- ADMINISTRACIÓN -->
                                <Dropdown v-if="hasRole('administrador')" align="left" width="56">
                                    <template #trigger>
                                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                            Administración
                                            <svg class="ms-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                        </button>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('usuarios.index')">Usuarios</DropdownLink>
                                        <DropdownLink :href="route('movimientos.index')">Auditoría</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- User menu -->
                        <div class="hidden md:flex md:items-center">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-emerald-600 transition">
                                        <span>{{ user?.nombre_usuario }}</span>
                                        <svg class="ms-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                </template>
                                <template #content>
                                    <div class="px-4 py-2 text-xs text-gray-400">
                                        Rol: <span class="capitalize">{{ user?.rol }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button" class="text-red-600">Cerrar Sesión</DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center md:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md hover:bg-emerald-600 transition"
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="md:hidden bg-emerald-800">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')">Dashboard</ResponsiveNavLink>
                        <template v-if="hasRole('administrador', 'archivo', 'medico')">
                            <ResponsiveNavLink :href="route('derecho_habientes.index')">Derechohabientes</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('expedientes.index')">Expedientes</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('documentos.index')">Documentos</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador', 'archivo')">
                            <ResponsiveNavLink :href="route('prestamos.index')">Préstamos</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('medicos.index')">Médicos</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('personal_archivos.index')">Personal Archivo</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador', 'medico', 'farmacia')">
                            <ResponsiveNavLink :href="route('recetas.index')">Recetas</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador', 'farmacia')">
                            <ResponsiveNavLink :href="route('medicamentos.index')">Medicamentos</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('dispensaciones.index')">Dispensaciones</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('entradas_farmacia.index')">Entradas Farmacia</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('salidas_farmacia.index')">Salidas Farmacia</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('farmacia.alertas')">Alertas</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador', 'almacen')">
                            <ResponsiveNavLink :href="route('productos.index')">Productos</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('proveedores.index')">Proveedores</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('entradas_almacen.index')">Entradas Almacén</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('salidas_almacen.index')">Salidas Almacén</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('transferencias.index')">Transferencias</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('almacen.existencias')">Existencias</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador', 'archivo', 'farmacia', 'almacen')">
                            <ResponsiveNavLink :href="route('solicitudes.index')">Solicitudes</ResponsiveNavLink>
                        </template>
                        <template v-if="hasRole('administrador')">
                            <ResponsiveNavLink :href="route('usuarios.index')">Usuarios</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('movimientos.index')">Auditoría</ResponsiveNavLink>
                        </template>
                    </div>
                    <div class="pt-3 pb-2 border-t border-emerald-600">
                        <div class="px-4">
                            <div class="font-medium text-sm">{{ user?.nombre_usuario }}</div>
                            <div class="text-xs text-emerald-200 capitalize">{{ user?.rol }}</div>
                        </div>
                        <div class="mt-2">
                            <form @submit.prevent="logout">
                                <ResponsiveNavLink as="button">Cerrar Sesión</ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="flex-1 w-full max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <FlashMessages />
                <slot />
            </main>

            <footer class="bg-emerald-700 text-emerald-100 text-center py-4 mt-8 text-sm">
                &copy; {{ new Date().getFullYear() }} Sistema Integral · Farmacia · Almacén · Archivo Clínico
            </footer>
        </div>
    </div>
</template>
