<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({
    proveedor: Object,
});

const form = useForm({
    nombre: props.proveedor.nombre ?? '',
    rfc: props.proveedor.rfc ?? '',
    telefono: props.proveedor.telefono ?? '',
    email: props.proveedor.email ?? '',
    direccion: props.proveedor.direccion ?? '',
    activo: Boolean(props.proveedor.activo),
});

const submit = () => form.put(route('proveedores.update', props.proveedor.id));
</script>

<template>
    <AppLayout title="Editar Proveedor">
        <PageHeader title="Editar Proveedor" />

        <Card title="Actualizar Proveedor">
            <form @submit.prevent="submit">
                <Form :form="form" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('proveedores.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
