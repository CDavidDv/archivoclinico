<script setup>
import { computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';

const props = defineProps({
    form: Object,
    roles: { type: Array, default: () => [] },
    esEdicion: { type: Boolean, default: false },
});

const rolOptions = computed(() => props.roles.map((r) => ({ value: r, label: r.charAt(0).toUpperCase() + r.slice(1) })));
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2">
        <TextField v-model="form.nombre_usuario" label="Nombre de usuario" :error="form.errors.nombre_usuario" required />
        <TextField v-model="form.email" label="Correo electrónico" type="email" :error="form.errors.email" required />
        <TextField v-model="form.telefono" label="Teléfono" :error="form.errors.telefono" />
        <SelectField v-model="form.rol" label="Rol" :options="rolOptions" placeholder="Seleccione rol" :error="form.errors.rol" required />
        <TextField v-model="form.password" label="Contraseña" type="password" :error="form.errors.password" :required="!esEdicion" :placeholder="esEdicion ? 'Dejar vacío para conservar' : ''" />
        <TextField v-model="form.password_confirmation" label="Confirmar contraseña" type="password" />
    </div>
</template>
