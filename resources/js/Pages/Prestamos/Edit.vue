<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ prestamo: Object, expedientes: Array, medicos: Array, personalArchivos: Array });

const iso = (f) => (f ? String(f).slice(0, 10) : '');

const form = useForm({
    id_expediente: props.prestamo.id_expediente ?? '',
    id_medico: props.prestamo.id_medico ?? '',
    entregado_por: props.prestamo.entregado_por ?? '',
    area_destino: props.prestamo.area_destino ?? '',
    fecha_salida: iso(props.prestamo.fecha_salida),
    fecha_regreso: iso(props.prestamo.fecha_regreso),
});
const submit = () => form.put(route('prestamos.update', props.prestamo.id));
</script>

<template>
    <AppLayout title="Editar Préstamo">
        <PageHeader :title="`Préstamo #${prestamo.id}`" />
        <Card title="Actualizar Préstamo">
            <form @submit.prevent="submit">
                <Form :form="form" :expedientes="expedientes" :medicos="medicos" :personal-archivos="personalArchivos" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('prestamos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
