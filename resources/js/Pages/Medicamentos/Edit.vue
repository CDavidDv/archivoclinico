<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ medicamento: Object, productos: Array });

const form = useForm({
    clave: props.medicamento.clave ?? '',
    nombre: props.medicamento.nombre ?? '',
    sustancia_activa: props.medicamento.sustancia_activa ?? '',
    presentacion: props.medicamento.presentacion ?? '',
    piezas_por_presentacion: props.medicamento.piezas_por_presentacion ?? 1,
    unidad_medida: props.medicamento.unidad_medida ?? 'pieza',
    stock_minimo: props.medicamento.stock_minimo ?? 0,
    controlado: Boolean(props.medicamento.controlado),
    dias_restriccion: props.medicamento.dias_restriccion ?? 28,
    id_producto: props.medicamento.id_producto ?? '',
    activo: Boolean(props.medicamento.activo),
});
const submit = () => form.put(route('medicamentos.update', props.medicamento.id));
</script>

<template>
    <AppLayout title="Editar Medicamento">
        <PageHeader :title="`${medicamento.clave} — ${medicamento.nombre}`" />
        <Card title="Actualizar Medicamento">
            <form @submit.prevent="submit">
                <Form :form="form" :productos="productos" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('medicamentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
