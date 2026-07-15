<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextField from '@/Components/Field/Text.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

const props = defineProps({ derechoHabientes: Array, medicos: Array, medicamentos: Array });

const dhOptions = computed(() => props.derechoHabientes.map((d) => ({ value: d.id, label: `${d.nombre} ${d.apellido_paterno} ${d.apellido_materno}` })));
const medicoOptions = computed(() => props.medicos.map((m) => ({ value: m.id, label: `${m.nombre} ${m.apellido_paterno}` })));
const medOptions = computed(() => props.medicamentos.map((m) => ({ value: m.id, label: `${m.clave} — ${m.nombre}` })));

const form = useForm({
    id_derecho_habiente: '', id_medico: '', fecha_receta: new Date().toISOString().slice(0, 10),
    diagnostico: '', indicaciones: '',
    detalles: [{ id_medicamento: '', cantidad_prescrita: 1, dosis: '' }],
});

const agregar = () => form.detalles.push({ id_medicamento: '', cantidad_prescrita: 1, dosis: '' });
const quitar = (i) => form.detalles.length > 1 && form.detalles.splice(i, 1);
const submit = () => form.post(route('recetas.store'));
</script>

<template>
    <AppLayout title="Nueva Receta">
        <PageHeader title="Nueva Receta" />
        <Card title="Registro de Receta">
            <form @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-3">
                    <SelectField v-model="form.id_derecho_habiente" label="Derechohabiente" :options="dhOptions" placeholder="Seleccione" :error="form.errors.id_derecho_habiente" required />
                    <SelectField v-model="form.id_medico" label="Médico" :options="medicoOptions" placeholder="Seleccione" :error="form.errors.id_medico" required />
                    <TextField v-model="form.fecha_receta" label="Fecha de receta" type="date" :error="form.errors.fecha_receta" required />
                    <div class="sm:col-span-3"><TextField v-model="form.diagnostico" label="Diagnóstico" :error="form.errors.diagnostico" /></div>
                    <div class="sm:col-span-3"><TextareaField v-model="form.indicaciones" label="Indicaciones" :error="form.errors.indicaciones" /></div>
                </div>

                <h3 class="mt-6 mb-2 text-sm font-semibold text-gray-700">Medicamentos prescritos</h3>
                <p v-if="form.errors.detalles" class="mb-2 text-sm text-red-600">{{ form.errors.detalles }}</p>

                <div v-for="(d, i) in form.detalles" :key="i" class="mb-3 grid grid-cols-12 gap-2">
                    <div class="col-span-5"><SelectField v-model="d.id_medicamento" :options="medOptions" placeholder="Medicamento" :error="form.errors[`detalles.${i}.id_medicamento`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.cantidad_prescrita" type="number" min="1" placeholder="Cant." :error="form.errors[`detalles.${i}.cantidad_prescrita`]" /></div>
                    <div class="col-span-4"><TextField v-model="d.dosis" placeholder="Dosis (ej. 1 c/8h)" :error="form.errors[`detalles.${i}.dosis`]" /></div>
                    <div class="col-span-1 flex items-start pt-1">
                        <button type="button" @click="quitar(i)" class="rounded border border-red-400 px-2 py-2 text-xs text-red-600 hover:bg-red-50" :disabled="form.detalles.length === 1">✕</button>
                    </div>
                </div>

                <button type="button" @click="agregar" class="mb-4 rounded-md border border-emerald-500 px-3 py-1.5 text-sm text-emerald-700 hover:bg-emerald-50">+ Agregar medicamento</button>

                <div class="mt-2 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Guardar receta</PrimaryButton>
                    <Link :href="route('recetas.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
