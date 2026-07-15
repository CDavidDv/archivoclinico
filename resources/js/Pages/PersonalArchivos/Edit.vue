<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ personalArchivo: Object });

const form = useForm({
    nombre: props.personalArchivo.nombre ?? '',
    apellido_paterno: props.personalArchivo.apellido_paterno ?? '',
    apellido_materno: props.personalArchivo.apellido_materno ?? '',
    rfc: props.personalArchivo.rfc ?? '',
    numero_empleado: props.personalArchivo.numero_empleado ?? '',
    cargo: props.personalArchivo.cargo ?? '',
    area: props.personalArchivo.area ?? '',
    tipo: props.personalArchivo.tipo ?? '',
});
const submit = () => form.put(route('personal_archivos.update', props.personalArchivo.id));
</script>

<template>
    <AppLayout title="Editar Personal de Archivo">
        <PageHeader :title="`${personalArchivo.nombre} ${personalArchivo.apellido_paterno}`" />
        <Card title="Actualizar Personal de Archivo">
            <form @submit.prevent="submit">
                <Form :form="form" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('personal_archivos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
