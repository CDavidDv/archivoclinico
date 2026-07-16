<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

defineProps({ productos: Array });

const form = useForm({
    clave: '', nombre: '', sustancia_activa: '', presentacion: '',
    piezas_por_presentacion: 1, unidad_medida: 'pieza', stock_minimo: 0,
    controlado: false, dias_restriccion: 28, id_producto: '', activo: true,
});
const submit = () => form.post(route('medicamentos.store'));
</script>

<template>
    <AppLayout title="Nuevo Medicamento">
        <PageHeader title="Nuevo Medicamento" />
        <Card title="Registrar Medicamento">
            <form @submit.prevent="submit">
                <Form :form="form" :productos="productos" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('medicamentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
