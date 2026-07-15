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

const props = defineProps({ medicamentos: Array });

const medOptions = computed(() => props.medicamentos.map((m) => ({ value: m.id, label: `${m.clave} — ${m.nombre}` })));

const form = useForm({
    fecha: new Date().toISOString().slice(0, 10),
    observaciones: '',
    detalles: [{ id_medicamento: '', numero_lote: '', caducidad: '', cantidad: 1 }],
});

const agregar = () => form.detalles.push({ id_medicamento: '', numero_lote: '', caducidad: '', cantidad: 1 });
const quitar = (i) => form.detalles.length > 1 && form.detalles.splice(i, 1);
const submit = () => form.post(route('entradas_farmacia.store'));
</script>

<template>
    <AppLayout title="Nueva Entrada de Farmacia">
        <PageHeader title="Nueva Entrada de Farmacia" />
        <Card title="Registro de Entrada">
            <form @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-2">
                    <TextField v-model="form.fecha" label="Fecha" type="date" :error="form.errors.fecha" required />
                    <div class="sm:col-span-2"><TextareaField v-model="form.observaciones" label="Observaciones" :error="form.errors.observaciones" /></div>
                </div>

                <h3 class="mt-6 mb-2 text-sm font-semibold text-gray-700">Lotes recibidos</h3>
                <p v-if="form.errors.detalles" class="mb-2 text-sm text-red-600">{{ form.errors.detalles }}</p>

                <div v-for="(d, i) in form.detalles" :key="i" class="mb-3 grid grid-cols-12 gap-2">
                    <div class="col-span-4"><SelectField v-model="d.id_medicamento" :options="medOptions" placeholder="Medicamento" :error="form.errors[`detalles.${i}.id_medicamento`]" /></div>
                    <div class="col-span-3"><TextField v-model="d.numero_lote" placeholder="Número de lote" :error="form.errors[`detalles.${i}.numero_lote`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.caducidad" type="date" :error="form.errors[`detalles.${i}.caducidad`]" /></div>
                    <div class="col-span-2"><TextField v-model="d.cantidad" type="number" min="1" placeholder="Cant." :error="form.errors[`detalles.${i}.cantidad`]" /></div>
                    <div class="col-span-1 flex items-start pt-1">
                        <button type="button" @click="quitar(i)" class="rounded border border-red-400 px-2 py-2 text-xs text-red-600 hover:bg-red-50" :disabled="form.detalles.length === 1">✕</button>
                    </div>
                </div>

                <button type="button" @click="agregar" class="mb-4 rounded-md border border-emerald-500 px-3 py-1.5 text-sm text-emerald-700 hover:bg-emerald-50">+ Agregar lote</button>

                <div class="mt-2 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Registrar entrada</PrimaryButton>
                    <Link :href="route('entradas_farmacia.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
