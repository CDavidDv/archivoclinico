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

const props = defineProps({ productos: Array, solicitud: Object });

const prodOptions = computed(() => props.productos.map((p) => ({ value: p.id, label: `${p.clave} — ${p.nombre} (stock ${p.stock_total ?? 0})` })));
const destinos = [
    { value: 'farmacia', label: 'Farmacia' },
    { value: 'area', label: 'Área' },
];

const detallesIniciales = props.solicitud?.detalles?.length
    ? props.solicitud.detalles.map((d) => ({ id_producto: d.id_producto, cantidad: d.cantidad_solicitada }))
    : [{ id_producto: '', cantidad: 1 }];

const form = useForm({
    destino: props.solicitud ? 'farmacia' : '',
    area_destino: '',
    id_solicitud: props.solicitud?.id ?? '',
    fecha: new Date().toISOString().slice(0, 10),
    observaciones: '',
    detalles: detallesIniciales,
});

const agregar = () => form.detalles.push({ id_producto: '', cantidad: 1 });
const quitar = (i) => form.detalles.length > 1 && form.detalles.splice(i, 1);
const submit = () => form.post(route('transferencias.store'));
</script>

<template>
    <AppLayout title="Nueva Transferencia">
        <PageHeader title="Nueva Transferencia" />
        <Card :title="solicitud ? `Surtir solicitud ${solicitud.folio}` : 'Registro de Transferencia'">
            <form @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-3">
                    <SelectField v-model="form.destino" label="Destino" :options="destinos" placeholder="Seleccione destino" :error="form.errors.destino" required />
                    <TextField v-if="form.destino === 'area'" v-model="form.area_destino" label="Área destino" :error="form.errors.area_destino" required />
                    <TextField v-model="form.fecha" label="Fecha" type="date" :error="form.errors.fecha" required />
                    <div class="sm:col-span-3"><TextareaField v-model="form.observaciones" label="Observaciones" :error="form.errors.observaciones" /></div>
                </div>

                <h3 class="mt-6 mb-2 text-sm font-semibold text-gray-700">Productos a transferir</h3>
                <p v-if="form.errors.detalles" class="mb-2 text-sm text-red-600">{{ form.errors.detalles }}</p>

                <div v-for="(d, i) in form.detalles" :key="i" class="mb-3 grid grid-cols-12 gap-2">
                    <div class="col-span-8"><SelectField v-model="d.id_producto" :options="prodOptions" placeholder="Producto" :error="form.errors[`detalles.${i}.id_producto`]" /></div>
                    <div class="col-span-3"><TextField v-model="d.cantidad" type="number" min="1" placeholder="Cant." :error="form.errors[`detalles.${i}.cantidad`]" /></div>
                    <div class="col-span-1 flex items-start pt-1">
                        <button type="button" @click="quitar(i)" class="rounded border border-red-400 px-2 py-2 text-xs text-red-600 hover:bg-red-50" :disabled="form.detalles.length === 1">✕</button>
                    </div>
                </div>

                <button type="button" @click="agregar" class="mb-4 rounded-md border border-emerald-500 px-3 py-1.5 text-sm text-emerald-700 hover:bg-emerald-50">+ Agregar producto</button>

                <div class="mt-2 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Registrar transferencia</PrimaryButton>
                    <Link :href="route('transferencias.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
