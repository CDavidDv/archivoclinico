<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const form = useForm({
    nombre: '', apellido_paterno: '', apellido_materno: '',
    rfc: '', numero_empleado: '', cargo: '', area: '', tipo: '',
});
const submit = () => form.post(route('medicos.store'));
</script>

<template>
    <AppLayout title="Nuevo Médico">
        <PageHeader title="Nuevo Médico" />
        <Card title="Registro de Médico">
            <form @submit.prevent="submit">
                <Form :form="form" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('medicos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
