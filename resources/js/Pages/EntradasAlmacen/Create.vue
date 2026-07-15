<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextField from '@/Components/Field/Text.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

const props = defineProps({ proveedores: Array, productos: Array, tipos: Array });

const provOptions = computed(() => props.proveedores.map((p) => ({ value: p.id, label: p.nombre })));
const prodOptions = computed(() => props.productos.map((p) => ({ value: p.id, label: `${p.clave} — ${p.nombre}` })));
const tipoOptions = computed(() => props.tipos.map((t) => ({ value: t, label: t.charAt(0).toUpperCase() + t.slice(1) })));

const form = useForm({
    id_proveedor: '', tipo: '', fecha: new Date().toISOString().slice(0, 10), observaciones: '',
    detalles: [{ id_producto: '', numero_lote: '', caducidad: '', cantidad: 1, precio_unitario: '' }],
});

const agregar = () => form.detalles.push({ id_producto: '', numero_lote: '', caducidad: '', cantidad: 1, precio_unitario: '' });
const quitar = (i) => form.detalles.length > 1 && form.detalles.splice(i, 1);
const submit = () => form.post(route('entradas_almacen.store'));
</script>

<template>
    <AppLayout title="Nueva Entrada de Almacén">
        <PageHeader title="Nueva Entrada de Almacén" />
        <Card title="Registro de Entrada">
            <form @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-3">
                    <SelectField v-model="form.tipo" label="Tipo" :options="tipoOptions" placeholder="Seleccione tipo" :error="form.errors.tipo" required />
                    <SelectField v-model="form.id_proveedor" label="Proveedor" :options="provOptions" placeholder="Sin proveedor" :error="form.errors.id_proveedor" />
                    <TextField v-model="form.fecha" label="Fecha" type="date" :error="form.errors.fecha" required />
                    <div class="sm:col-span-3"><TextareaField v-model="form.observaciones" label="Observaciones" :error="form.errors.observaciones" /></div>
                </div>

                <h3 class="mt-6 mb-2 text-sm font-semibold text-gray-700">Productos recibidos</h3>
                <p v-if="form.errors.detalles" class="mb-2 text-sm text-red-600">{{ form.errors.detalles }}</p>

                <div v-for="(d, i) in form.detalles" :key="i" class="mb-3 grid grid-cols-12 gap-2">
                    <div class="col-span-3"><SelectField v-model="d.id_producto" :options="prodOptions" placeholder="Producto" :error="form.errors[`detalles.${i}.id_producto`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.numero_lote" placeholder="Lote" :error="form.errors[`detalles.${i}.numero_lote`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.caducidad" type="date" :error="form.errors[`detalles.${i}.caducidad`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.cantidad" type="number" min="1" placeholder="Cant." :error="form.errors[`detalles.${i}.cantidad`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.precio_unitario" type="number" min="0" step="0.01" placeholder="Precio" :error="form.errors[`detalles.${i}.precio_unitario`]" /></div>
                    <div class="col-span-1 flex items-start pt-1">
                        <button type="button" @click="quitar(i)" class="rounded border border-red-400 px-2 py-2 text-xs text-red-600 hover:bg-red-50" :disabled="form.detalles.length === 1">✕</button>
                    </div>
                </div>

                <button type="button" @click="agregar" class="mb-4 rounded-md border border-emerald-500 px-3 py-1.5 text-sm text-emerald-700 hover:bg-emerald-50">+ Agregar producto</button>

                <div class="mt-2 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Registrar entrada</PrimaryButton>
                    <Link :href="route('entradas_almacen.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
