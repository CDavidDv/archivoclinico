<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

const props = defineProps({ usuario: Object, roles: Array });

const form = useForm({
    nombre_usuario: props.usuario.nombre_usuario ?? '',
    email: props.usuario.email ?? '',
    telefono: props.usuario.telefono ?? '',
    rol: props.usuario.rol ?? '',
    password: '', password_confirmation: '',
});
const submit = () => form.put(route('usuarios.update', props.usuario.id));
</script>

<template>
    <AppLayout title="Editar Usuario">
        <PageHeader :title="usuario.nombre_usuario" />
        <Card title="Actualizar Usuario">
            <form @submit.prevent="submit">
                <Form :form="form" :roles="roles" :es-edicion="true" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Actualizar</PrimaryButton>
                    <Link :href="route('usuarios.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
