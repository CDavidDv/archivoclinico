<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ documento: Object, expedientes: Array });

const form = useForm({
    _method: 'put',
    id_expediente: props.documento.id_expediente ?? '',
    nombre_documento: props.documento.nombre_documento ?? '',
    ruta_archivo: null,
    fecha_anexo: props.documento.fecha_anexo ?? '',
});
const submit = () => form.post(route('documentos.update', props.documento.id));
</script>

<template>
    <AppLayout title="Editar Documento">
        <PageHeader :title="documento.nombre_documento" />
        <Card title="Actualizar Documento">
            <form @submit.prevent="submit">
                <Form :form="form" :expedientes="expedientes" :archivo-actual="documento.nombre_original" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('documentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
                <p v-if="form.progress" class="mt-2 text-xs text-gray-500">Subiendo… {{ form.progress.percentage }}%</p>
            </form>
        </Card>
    </AppLayout>
</template>
