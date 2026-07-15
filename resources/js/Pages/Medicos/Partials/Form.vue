<script setup>
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';

defineProps({ form: Object });

const tipos = [
    { value: 'base', label: 'Base' },
    { value: 'confianza', label: 'Confianza' },
    { value: 'residente', label: 'Residente' },
    { value: 'pasante', label: 'Pasante' },
    { value: 'suplente', label: 'Suplente' },
    { value: 'interino', label: 'Interino' },
    { value: 'interno', label: 'Interno' },
];
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-3">
        <TextField v-model="form.nombre" label="Nombre" :error="form.errors.nombre" required />
        <TextField v-model="form.apellido_paterno" label="Apellido paterno" :error="form.errors.apellido_paterno" required />
        <TextField v-model="form.apellido_materno" label="Apellido materno" :error="form.errors.apellido_materno" required />
        <TextField v-model="form.rfc" label="RFC" :error="form.errors.rfc" required maxlength="13" />
        <TextField v-model="form.numero_empleado" label="Número de empleado" :error="form.errors.numero_empleado" required />
        <TextField v-model="form.cargo" label="Cargo" :error="form.errors.cargo" required />
        <div class="sm:col-span-2">
            <TextField v-model="form.area" label="Área" :error="form.errors.area" required />
        </div>
        <SelectField v-model="form.tipo" label="Denominación" :options="tipos" placeholder="Seleccione..." :error="form.errors.tipo" required />
    </div>
</template>
