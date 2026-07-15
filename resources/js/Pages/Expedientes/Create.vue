<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

defineProps({ derechoHabientes: Array });

const form = useForm({
    codigo: '', id_derecho_habiente: '', localizacion: 'Archivo Clínico',
    tipo: 'normal', fecha_creacion: '',
});
const submit = () => form.post(route('expedientes.store'));
</script>

<template>
    <AppLayout title="Nuevo Expediente">
        <PageHeader title="Nuevo Expediente" />
        <Card title="Registro de Expediente">
            <form @submit.prevent="submit">
                <Form :form="form" :derecho-habientes="derechoHabientes" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('expedientes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
