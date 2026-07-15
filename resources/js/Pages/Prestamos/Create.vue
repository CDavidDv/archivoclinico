<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

defineProps({ expedientes: Array, medicos: Array, personalArchivos: Array });

const form = useForm({
    id_expediente: '', id_medico: '', entregado_por: '',
    area_destino: '', fecha_salida: '', fecha_regreso: '',
});
const submit = () => form.post(route('prestamos.store'));
</script>

<template>
    <AppLayout title="Nuevo Préstamo">
        <PageHeader title="Nuevo Préstamo" />
        <Card title="Registro de Préstamo">
            <form @submit.prevent="submit">
                <Form :form="form" :expedientes="expedientes" :medicos="medicos" :personal-archivos="personalArchivos" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('prestamos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
