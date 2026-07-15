<script setup>
import { reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import Pagination from '@/Components/Pagination.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextField from '@/Components/Field/Text.vue';

const props = defineProps({ movimientos: Object, usuarios: Array });

const fecha = (f) => (f ? new Date(f).toLocaleString('es-MX') : '—');

const q = new URLSearchParams(window.location.search);
const filtros = reactive({
    usuario: q.get('usuario') ?? '',
    accion: q.get('accion') ?? '',
    desde: q.get('desde') ?? '',
    hasta: q.get('hasta') ?? '',
});

const usuarioOptions = computed(() => props.usuarios.map((u) => ({ value: u.id, label: u.nombre_usuario })));

const aplicar = () => {
    const params = Object.fromEntries(Object.entries(filtros).filter(([, v]) => v !== ''));
    router.get(route('movimientos.index'), params, { preserveState: true });
};
const limpiar = () => {
    filtros.usuario = ''; filtros.accion = ''; filtros.desde = ''; filtros.hasta = '';
    router.get(route('movimientos.index'));
};
</script>

<template>
    <AppLayout title="Bitácora de Movimientos">
        <PageHeader title="Bitácora de Movimientos" />

        <Card title="Filtros" class="mb-6">
            <div class="grid gap-4 sm:grid-cols-4">
                <SelectField v-model="filtros.usuario" label="Usuario" :options="usuarioOptions" placeholder="Todos" />
                <TextField v-model="filtros.accion" label="Acción" placeholder="crear, actualizar…" />
                <TextField v-model="filtros.desde" label="Desde" type="date" />
                <TextField v-model="filtros.hasta" label="Hasta" type="date" />
            </div>
            <div class="mt-4 flex gap-2">
                <button @click="aplicar" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Filtrar</button>
                <button @click="limpiar" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Limpiar</button>
            </div>
        </Card>

        <Card title="Registros">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Fecha</th>
                            <th class="px-3 py-2 font-medium">Usuario</th>
                            <th class="px-3 py-2 font-medium">Acción</th>
                            <th class="px-3 py-2 font-medium">Tabla</th>
                            <th class="px-3 py-2 font-medium">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="m in movimientos.data" :key="m.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2">{{ fecha(m.fecha_accion) }}</td>
                            <td class="px-3 py-2">{{ m.usuario ? m.usuario.nombre_usuario : '—' }}</td>
                            <td class="px-3 py-2 capitalize">{{ m.accion }}</td>
                            <td class="px-3 py-2">{{ m.tabla_afectada }}</td>
                            <td class="px-3 py-2">{{ m.id_registro_afectado ?? '—' }}</td>
                        </tr>
                        <tr v-if="movimientos.data.length === 0"><td colspan="5" class="px-3 py-6 text-center text-gray-400">Sin movimientos.</td></tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="movimientos.links" />
        </Card>
    </AppLayout>
</template>
