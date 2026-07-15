<script setup>
import { computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';

const props = defineProps({
    form: Object,
    expedientes: { type: Array, default: () => [] },
    medicos: { type: Array, default: () => [] },
    personalArchivos: { type: Array, default: () => [] },
});

const expOptions = computed(() => props.expedientes.map((e) => ({ value: e.id, label: e.codigo })));
const medicoOptions = computed(() => props.medicos.map((m) => ({ value: m.id, label: `${m.nombre} ${m.apellido_paterno} ${m.apellido_materno}` })));
const personalOptions = computed(() => props.personalArchivos.map((p) => ({ value: p.id, label: `${p.nombre} ${p.apellido_paterno}` })));
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2">
        <SelectField v-model="form.id_expediente" label="Expediente" :options="expOptions" placeholder="Seleccione expediente" :error="form.errors.id_expediente" required />
        <SelectField v-model="form.id_medico" label="Médico solicitante" :options="medicoOptions" placeholder="Seleccione médico" :error="form.errors.id_medico" required />
        <SelectField v-model="form.entregado_por" label="Entregado por" :options="personalOptions" placeholder="Seleccione personal" :error="form.errors.entregado_por" required />
        <TextField v-model="form.area_destino" label="Área destino" :error="form.errors.area_destino" required />
        <TextField v-model="form.fecha_salida" label="Fecha de salida" type="date" :error="form.errors.fecha_salida" required />
        <TextField v-model="form.fecha_regreso" label="Fecha de regreso" type="date" :error="form.errors.fecha_regreso" required />
    </div>
</template>
