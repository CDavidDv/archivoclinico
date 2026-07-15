<script setup>
import { computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';

const props = defineProps({
    form: Object,
    derechoHabientes: { type: Array, default: () => [] },
});

const dhOptions = computed(() =>
    props.derechoHabientes.map((d) => ({
        value: d.id,
        label: `${d.nombre} ${d.apellido_paterno} ${d.apellido_materno} - ${d.rfc}`,
    }))
);

const tipos = [
    { value: 'normal', label: 'Normal' },
    { value: 'gordo', label: 'Gordo' },
    { value: 'confidencial', label: 'Confidencial' },
];
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2">
        <TextField v-model="form.codigo" label="Código" :error="form.errors.codigo" required />
        <SelectField v-model="form.id_derecho_habiente" label="Derechohabiente" :options="dhOptions" placeholder="Seleccione un derechohabiente" :error="form.errors.id_derecho_habiente" required />
        <TextField v-model="form.localizacion" label="Localización" :error="form.errors.localizacion" />
        <SelectField v-model="form.tipo" label="Tipo" :options="tipos" :error="form.errors.tipo" />
        <TextField v-model="form.fecha_creacion" label="Fecha de creación" type="date" :error="form.errors.fecha_creacion" required />
    </div>
</template>
