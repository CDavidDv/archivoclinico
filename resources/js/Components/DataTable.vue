<script setup>
import { reactive, computed, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

/*
 * Tabla reutilizable con:
 *  - Buscador por columna (texto / select / fecha), filtrado en el servidor.
 *  - Botón para remover todos los filtros.
 *  - Paginación (usa el paginador de Laravel).
 *
 * columns: [{ key, label, filter: false|'text'|'select'|'date', options, align, filterKey }]
 * Slots:  #col-<key>="{ row }"  celda personalizada
 *         #actions="{ row }"    columna de acciones (a la derecha)
 */
const props = defineProps({
    columns: { type: Array, required: true },
    paginator: { type: Object, required: true }, // { data, links, ... }
    routeName: { type: String, required: true },
    routeParams: { type: Object, default: () => ({}) },
    filters: { type: Object, default: () => ({}) },
    empty: { type: String, default: 'No hay registros.' },
    hasActions: { type: Boolean, default: false },
});

const filtrables = computed(() => props.columns.filter((c) => c.filter));

// Estado reactivo de los filtros, sembrado con lo que devolvió el servidor.
const filtros = reactive({});
props.columns.forEach((c) => {
    if (c.filter) {
        const k = c.filterKey || c.key;
        filtros[k] = props.filters?.[k] ?? '';
    }
});

const hayFiltros = computed(() => Object.values(filtros).some((v) => v !== '' && v != null));

const aplicar = () => {
    const activos = Object.fromEntries(Object.entries(filtros).filter(([, v]) => v !== '' && v != null));
    router.get(route(props.routeName, props.routeParams), activos, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Debounce para las entradas de texto.
let t = null;
const aplicarDebounce = () => {
    clearTimeout(t);
    t = setTimeout(aplicar, 350);
};

const limpiar = () => {
    Object.keys(filtros).forEach((k) => (filtros[k] = ''));
    aplicar();
};

const rows = computed(() => props.paginator?.data ?? []);
const colspan = computed(() => props.columns.length + (props.hasActions ? 1 : 0));
const alignClass = (c) => (c.align === 'right' ? 'text-right' : c.align === 'center' ? 'text-center' : 'text-left');
</script>

<template>
    <div>
        <div v-if="hayFiltros" class="mb-3 flex justify-end">
            <button type="button" @click="limpiar"
                class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                Remover filtros
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th v-for="c in columns" :key="c.key" class="px-3 py-2 font-medium" :class="alignClass(c)">{{ c.label }}</th>
                        <th v-if="hasActions" class="px-3 py-2 font-medium text-right">Acciones</th>
                    </tr>
                    <tr v-if="filtrables.length" class="align-top">
                        <th v-for="c in columns" :key="c.key" class="px-2 pb-2">
                            <template v-if="c.filter === 'select'">
                                <select v-model="filtros[c.filterKey || c.key]" @change="aplicar"
                                    class="w-full rounded-md border-gray-300 py-1 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Todos</option>
                                    <option v-for="o in c.options" :key="o.value" :value="o.value">{{ o.label }}</option>
                                </select>
                            </template>
                            <template v-else-if="c.filter === 'date'">
                                <input type="date" v-model="filtros[c.filterKey || c.key]" @change="aplicar"
                                    class="w-full rounded-md border-gray-300 py-1 text-xs focus:border-emerald-500 focus:ring-emerald-500" />
                            </template>
                            <template v-else-if="c.filter">
                                <input type="text" v-model="filtros[c.filterKey || c.key]" @input="aplicarDebounce" placeholder="Buscar…"
                                    class="w-full rounded-md border-gray-300 py-1 text-xs focus:border-emerald-500 focus:ring-emerald-500" />
                            </template>
                        </th>
                        <th v-if="hasActions"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-for="row in rows" :key="row.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                        <td v-for="c in columns" :key="c.key" class="px-3 py-2" :class="alignClass(c)">
                            <slot :name="`col-${c.key}`" :row="row">{{ row[c.key] }}</slot>
                        </td>
                        <td v-if="hasActions" class="px-3 py-2">
                            <div class="flex justify-end gap-1"><slot name="actions" :row="row" /></div>
                        </td>
                    </tr>
                    <tr v-if="rows.length === 0">
                        <td :colspan="colspan" class="px-3 py-6 text-center text-gray-400">{{ empty }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="paginator.links ?? []" />
    </div>
</template>
