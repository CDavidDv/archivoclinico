<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({ productos: Array, categoria: String, categorias: Array });

const filtrar = (c) => router.get(route('almacen.existencias'), c ? { categoria: c } : {}, { preserveState: true });
const bajo = (p) => (p.stock_total ?? 0) <= p.stock_minimo;
</script>

<template>
    <AppLayout title="Reporte de Existencias">
        <PageHeader title="Reporte de Existencias" />

        <div class="mb-4 flex flex-wrap gap-1">
            <button @click="filtrar('')" :class="!categoria ? 'bg-emerald-600 text-white' : 'border border-gray-300 text-gray-700'" class="rounded px-3 py-1 text-sm">Todas</button>
            <button v-for="c in categorias" :key="c" @click="filtrar(c)" :class="categoria === c ? 'bg-emerald-600 text-white' : 'border border-gray-300 text-gray-700'" class="rounded px-3 py-1 text-sm capitalize">{{ c }}</button>
        </div>

        <Card title="Existencias por producto">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="px-3 py-2 font-medium">Clave</th>
                            <th class="px-3 py-2 font-medium">Producto</th>
                            <th class="px-3 py-2 font-medium">Categoría</th>
                            <th class="px-3 py-2 font-medium">Stock total</th>
                            <th class="px-3 py-2 font-medium">Mínimo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="p in productos" :key="p.id" :class="bajo(p) ? 'bg-red-50/50' : ''">
                            <td class="px-3 py-2 font-semibold">{{ p.clave }}</td>
                            <td class="px-3 py-2">{{ p.nombre }}</td>
                            <td class="px-3 py-2 capitalize">{{ p.categoria }}</td>
                            <td class="px-3 py-2">
                                <span :class="bajo(p) ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-800'" class="rounded-full px-2 py-0.5 text-xs font-medium">{{ p.stock_total ?? 0 }}</span>
                            </td>
                            <td class="px-3 py-2">{{ p.stock_minimo }}</td>
                        </tr>
                        <tr v-if="productos.length === 0"><td colspan="5" class="px-3 py-6 text-center text-gray-400">Sin productos.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </AppLayout>
</template>
