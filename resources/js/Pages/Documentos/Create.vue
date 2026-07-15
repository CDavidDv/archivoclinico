<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

defineProps({ expedientes: Array });

const form = useForm({
    id_expediente: '', nombre_documento: '', ruta_archivo: null,
    fecha_anexo: new Date().toISOString().slice(0, 10),
});
const submit = () => form.post(route('documentos.store'));
</script>

<template>
    <AppLayout title="Nuevo Documento">
        <PageHeader title="Nuevo Documento" />
        <Card title="Registro de Documento">
            <form @submit.prevent="submit">
                <Form :form="form" :expedientes="expedientes" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('documentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
                <p v-if="form.progress" class="mt-2 text-xs text-gray-500">Subiendo… {{ form.progress.percentage }}%</p>
            </form>
        </Card>
    </AppLayout>
</template>
