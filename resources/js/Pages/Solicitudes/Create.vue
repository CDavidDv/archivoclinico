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

const props = defineProps({ productos: Array, modulo: String });

const productoOptions = computed(() => props.productos.map((p) => ({ value: p.id, label: `${p.clave} — ${p.nombre}` })));

const form = useForm({
    observaciones: '',
    detalles: [{ id_producto: '', cantidad_solicitada: 1 }],
});

const agregar = () => form.detalles.push({ id_producto: '', cantidad_solicitada: 1 });
const quitar = (i) => form.detalles.length > 1 && form.detalles.splice(i, 1);
const submit = () => form.post(route('solicitudes.store'));
</script>

<template>
    <AppLayout title="Nueva Solicitud">
        <PageHeader :title="`Nueva Solicitud — ${modulo}`" />
        <Card title="Registro de Solicitud de Abastecimiento">
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <TextareaField v-model="form.observaciones" label="Observaciones" :error="form.errors.observaciones" />
                </div>

                <h3 class="mb-2 text-sm font-semibold text-gray-700">Productos solicitados</h3>
                <p v-if="form.errors.detalles" class="mb-2 text-sm text-red-600">{{ form.errors.detalles }}</p>

                <div v-for="(d, i) in form.detalles" :key="i" class="mb-3 grid grid-cols-12 gap-2">
                    <div class="col-span-8">
                        <SelectField v-model="d.id_producto" :options="productoOptions" placeholder="Seleccione producto" :error="form.errors[`detalles.${i}.id_producto`]" />
                    </div>
                    <div class="col-span-3">
                        <TextField v-model="d.cantidad_solicitada" type="number" min="1" placeholder="Cantidad" :error="form.errors[`detalles.${i}.cantidad_solicitada`]" />
                    </div>
                    <div class="col-span-1 flex items-start pt-1">
                        <button type="button" @click="quitar(i)" class="rounded border border-red-400 px-2 py-2 text-xs text-red-600 hover:bg-red-50" :disabled="form.detalles.length === 1">✕</button>
                    </div>
                </div>

                <button type="button" @click="agregar" class="mb-4 rounded-md border border-emerald-500 px-3 py-1.5 text-sm text-emerald-700 hover:bg-emerald-50">+ Agregar producto</button>

                <div class="mt-2 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Enviar solicitud</PrimaryButton>
                    <Link :href="route('solicitudes.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
