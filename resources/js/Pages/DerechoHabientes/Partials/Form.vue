<script setup>
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

defineProps({ form: Object });

const claves = [
    { value: '10', label: '10 - Trabajador' }, { value: '20', label: '20 - Trabajadora' },
    { value: '30', label: '30 - Esposa' }, { value: '31', label: '31 - Concubina' },
    { value: '32', label: '32 - Mujer' }, { value: '40', label: '40 - Esposo' },
    { value: '41', label: '41 - Concubino' }, { value: '50', label: '50 - Padre' },
    { value: '51', label: '51 - Abuelo' }, { value: '60', label: '60 - Madre' },
    { value: '61', label: '61 - Abuela' }, { value: '70', label: '70 - Hijo' },
    { value: '71', label: '71 - Hijo de Cónyuge' }, { value: '80', label: '80 - Hija' },
    { value: '81', label: '81 - Hija de Cónyuge' }, { value: '90', label: '90 - Pensionado' },
    { value: '91', label: '91 - Pensionada' }, { value: '92', label: '92 - Familiar de pensionado' },
    { value: '95', label: '95 - IMSS Bienestar Hombre' }, { value: '96', label: '96 - IMSS Bienestar Mujer' },
    { value: '97', label: '97 - IMSS Hombre' }, { value: '98', label: '98 - IMSS Mujer' },
    { value: '99', label: '99 - No Derechohabiente' },
];

const generos = ['Masculino', 'Femenino'];
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-3">
        <TextField v-model="form.nombre" label="Nombre" :error="form.errors.nombre" required />
        <TextField v-model="form.apellido_paterno" label="Apellido paterno" :error="form.errors.apellido_paterno" required />
        <TextField v-model="form.apellido_materno" label="Apellido materno" :error="form.errors.apellido_materno" required />
        <TextField v-model="form.rfc" label="RFC" :error="form.errors.rfc" required maxlength="13" />
        <TextField v-model="form.nss" label="NSS" :error="form.errors.nss" required />
        <SelectField v-model="form.clave_identificacion" label="Clave de identificación" :options="claves" placeholder="Seleccione una clave" :error="form.errors.clave_identificacion" required />
        <TextField v-model="form.fecha_nacimiento" label="Fecha de nacimiento" type="date" :error="form.errors.fecha_nacimiento" required />
        <SelectField v-model="form.genero" label="Género" :options="generos" :error="form.errors.genero" required />
        <div class="sm:col-span-3">
            <TextareaField v-model="form.sintomas" label="Síntomas o motivo" :error="form.errors.sintomas" />
        </div>
        <div class="sm:col-span-3">
            <TextareaField v-model="form.tratamiento" label="Tratamiento / resumen médico" :error="form.errors.tratamiento" />
        </div>
    </div>
</template>
