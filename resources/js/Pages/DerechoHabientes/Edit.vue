<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ dh: Object });

const form = useForm({
    nombre: props.dh.nombre ?? '',
    apellido_paterno: props.dh.apellido_paterno ?? '',
    apellido_materno: props.dh.apellido_materno ?? '',
    rfc: props.dh.rfc ?? '',
    nss: props.dh.nss ?? '',
    clave_identificacion: props.dh.clave_identificacion ?? '',
    fecha_nacimiento: props.dh.fecha_nacimiento ?? '',
    genero: props.dh.genero ?? 'Masculino',
    sintomas: props.dh.sintomas ?? '',
    tratamiento: props.dh.tratamiento ?? '',
});
const submit = () => form.put(route('derecho_habientes.update', props.dh.id));
</script>

<template>
    <AppLayout title="Editar Derechohabiente">
        <PageHeader :title="`${dh.nombre} ${dh.apellido_paterno}`" />
        <Card title="Actualizar Derechohabiente">
            <form @submit.prevent="submit">
                <Form :form="form" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('derecho_habientes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
