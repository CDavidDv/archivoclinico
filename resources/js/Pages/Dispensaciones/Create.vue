<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextareaField from '@/Components/Field/Textarea.vue';

const props = defineProps({ receta: Object, stockDisponible: Object, restricciones: Object });

const nom = (p) => (p ? `${p.nombre} ${p.apellido_paterno}` : '—');
const pendiente = (d) => (d.cantidad_prescrita ?? 0) - (d.cantidad_surtida ?? 0);
const stock = (id) => props.stockDisponible?.[id] ?? 0;
const restr = (id) => props.restricciones?.[id] ?? {};

const cantidades = {};
props.receta.detalles.forEach((d) => { cantidades[d.id] = 0; });

const form = useForm({
    cantidades,
    observaciones: '',
    autorizacion: { nombre_usuario: '', password: '', motivo: '' },
});

// Renglón que excede lo pendiente (por error de captura o presentación comercial).
const excede = (d) => (form.cantidades[d.id] || 0) > pendiente(d);
// Controlado con surtido anticipado bloqueado.
const controladoBloq = (d) => (form.cantidades[d.id] || 0) > 0 && restr(d.id).bloqueado;

const necesitaAutorizacion = computed(() =>
    props.receta.detalles.some((d) => excede(d) || controladoBloq(d))
);

const submit = () => {
    // Sólo se envían credenciales cuando se requieren.
    const data = form.transform((d) => ({
        ...d,
        autorizacion: necesitaAutorizacion.value ? d.autorizacion : { nombre_usuario: '', password: '', motivo: '' },
    }));
    data.post(route('dispensaciones.store', props.receta.id));
};
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
                                <td class="px-3 py-2">
                                    <div>{{ d.medicamento ? `${d.medicamento.clave} — ${d.medicamento.nombre}` : '—' }}</div>
                                    <div class="mt-1 flex flex-wrap gap-1">
                                        <span v-if="d.medicamento && d.medicamento.piezas_por_presentacion > 1"
                                              class="inline-block rounded bg-sky-100 px-1.5 py-0.5 text-[11px] text-sky-700">
                                            Caja de {{ d.medicamento.piezas_por_presentacion }}
                                        </span>
                                        <span v-if="restr(d.id).controlado"
                                              class="inline-block rounded bg-purple-100 px-1.5 py-0.5 text-[11px] text-purple-700">
                                            Controlado
                                        </span>
                                        <span v-if="restr(d.id).bloqueado"
                                              class="inline-block rounded bg-red-100 px-1.5 py-0.5 text-[11px] text-red-700">
                                            Restringido hasta {{ restr(d.id).disponible_desde }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-3 py-2">{{ d.cantidad_prescrita }}</td>
                                <td class="px-3 py-2">{{ pendiente(d) }}</td>
                                <td class="px-3 py-2">
                                    <span :class="stock(d.id) <= 0 ? 'text-red-600' : 'text-emerald-700'">{{ stock(d.id) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <input
                                        type="number" min="0" :max="stock(d.id)"
                                        v-model.number="form.cantidades[d.id]"
                                        :class="[
                                            'w-24 rounded-md text-sm focus:ring-emerald-500',
                                            (excede(d) || controladoBloq(d)) ? 'border-amber-400 focus:border-amber-500' : 'border-gray-300 focus:border-emerald-500',
                                        ]"
                                    />
                                    <p v-if="excede(d)" class="mt-1 text-[11px] text-amber-600">Excede lo pendiente</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-if="form.errors.cantidades" class="mt-2 text-sm text-red-600">{{ form.errors.cantidades }}</p>
                <p v-if="form.errors.detalles" class="mt-2 text-sm text-red-600">{{ form.errors.detalles }}</p>
                <p v-if="form.errors.autorizacion_requerida" class="mt-2 text-sm text-red-600">{{ form.errors.autorizacion_requerida }}</p>

                <!-- Panel de autorización (jefe de área) -->
                <div v-if="necesitaAutorizacion" class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-4 dark:bg-amber-900/20">
                    <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Se requiere autorización del jefe de área</h4>
                    <p class="mt-1 text-xs text-amber-700 dark:text-amber-400">
                        Un usuario administrador debe autorizar el surtido que excede lo prescrito o de un medicamento controlado.
                    </p>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Usuario que autoriza</label>
                            <input v-model="form.autorizacion.nombre_usuario" type="text" autocomplete="off"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Contraseña</label>
                            <input v-model="form.autorizacion.password" type="password" autocomplete="new-password"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-600">Motivo de la autorización</label>
                            <input v-model="form.autorizacion.motivo" type="text"
                                   class="mt-1 w-full rounded-md border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500" />
                        </div>
                    </div>
                    <p v-if="form.errors.autorizacion" class="mt-2 text-sm text-red-600">{{ form.errors.autorizacion }}</p>
                    <p v-if="form.errors['autorizacion.motivo']" class="mt-1 text-sm text-red-600">{{ form.errors['autorizacion.motivo'] }}</p>
                    <p v-if="form.errors['autorizacion.nombre_usuario']" class="mt-1 text-sm text-red-600">{{ form.errors['autorizacion.nombre_usuario'] }}</p>
                    <p v-if="form.errors['autorizacion.password']" class="mt-1 text-sm text-red-600">{{ form.errors['autorizacion.password'] }}</p>
                </div>

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
