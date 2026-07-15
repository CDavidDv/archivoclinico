<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Form from './Partials/Form.vue';

defineProps({ roles: Array });

const form = useForm({
    nombre_usuario: '', email: '', telefono: '', rol: '',
    password: '', password_confirmation: '',
});
const submit = () => form.post(route('usuarios.store'));
</script>

<template>
    <AppLayout title="Nuevo Usuario">
        <PageHeader title="Nuevo Usuario" />
        <Card title="Registro de Usuario">
            <form @submit.prevent="submit">
                <Form :form="form" :roles="roles" />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar</PrimaryButton>
                    <Link :href="route('usuarios.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
