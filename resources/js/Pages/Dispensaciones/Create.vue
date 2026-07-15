<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

const props = defineProps({ receta: Object, stockDisponible: Object });

const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
const pendiente = (d) => (d.cantidad_prescrita ?? 0) - (d.cantidad_surtida ?? 0);
const stock = (id) => props.stockDisponible?.[id] ?? 0;

const cantidades = {};
props.receta.detalles.forEach((d) => { cantidades[d.id] = 0; });

const form = useForm({ cantidades, observaciones: '' });
const submit = () => form.post(route('dispensaciones.store', props.receta.id));
</script>

<template>
    <AppLayout title="Dispensar Receta">
        <PageHeader :title="`Dispensar — ${receta.folio}`" />
        <Card :title="`Derechohabiente: ${nom(receta.derecho_habiente)}`">
            <form @submit.prevent="submit">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="px-3 py-2 font-medium">Medicamento</th>
                                <th class="px-3 py-2 font-medium">Prescrito</th>
                                <th class="px-3 py-2 font-medium">Pendiente</th>
                                <th class="px-3 py-2 font-medium">Stock</th>
                                <th class="px-3 py-2 font-medium">A dispensar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="d in receta.detalles" :key="d.id">
                                <td class="px-3 py-2">{{ d.medicamento ? `${d.medicamento.clave} — ${d.medicamento.nombre}` : '—' }}</td>
                                <td class="px-3 py-2">{{ d.cantidad_prescrita }}</td>
                                <td class="px-3 py-2">{{ pendiente(d) }}</td>
                                <td class="px-3 py-2">
                                    <span :class="stock(d.id) <= 0 ? 'text-red-600' : 'text-emerald-700'">{{ stock(d.id) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <input
                                        type="number" min="0" :max="Math.min(pendiente(d), stock(d.id))"
                                        v-model.number="form.cantidades[d.id]"
                                        class="w-24 rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-if="form.errors.cantidades" class="mt-2 text-sm text-red-600">{{ form.errors.cantidades }}</p>

                <div class="mt-4">
                    <TextareaField v-model="form.observaciones" label="Observaciones" :error="form.errors.observaciones" />
                </div>

                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Dispensar</PrimaryButton>
                    <Link :href="route('recetas.show', receta.id)" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
