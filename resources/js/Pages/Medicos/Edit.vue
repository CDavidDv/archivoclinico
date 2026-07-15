<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ medico: Object });

const form = useForm({
    nombre: props.medico.nombre ?? '',
    apellido_paterno: props.medico.apellido_paterno ?? '',
    apellido_materno: props.medico.apellido_materno ?? '',
    rfc: props.medico.rfc ?? '',
    numero_empleado: props.medico.numero_empleado ?? '',
    cargo: props.medico.cargo ?? '',
    area: props.medico.area ?? '',
    tipo: props.medico.tipo ?? '',
});
const submit = () => form.put(route('medicos.update', props.medico.id));
</script>

<template>
    <AppLayout title="Editar Médico">
        <PageHeader :title="`${medico.nombre} ${medico.apellido_paterno}`" />
        <Card title="Actualizar Médico">
            <form @submit.prevent="submit">
                <Form :form="form" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('medicos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
