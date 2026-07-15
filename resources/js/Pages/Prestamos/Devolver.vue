<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectField from '@/Components/Field/Select.vue';

const props = defineProps({ prestamo: Object, personalArchivos: Array });

const fecha = (f) => (f ? new Date(f).toLocaleDateString('es-MX') : '—');
const personalOptions = computed(() => props.personalArchivos.map((p) => ({ value: p.id, label: `${p.nombre} ${p.apellido_paterno}` })));

const form = useForm({ recibido_por: '' });
const submit = () => form.put(route('prestamos.procesarDevolucion', props.prestamo.id));
</script>

<template>
    <AppLayout title="Devolver Préstamo">
        <PageHeader :title="`Devolver Préstamo #${prestamo.id}`" />
        <Card title="Confirmación de Devolución">
            <dl class="mb-6 grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
                <div><dt class="text-gray-500">Expediente</dt><dd class="font-medium">{{ prestamo.expediente ? prestamo.expediente.codigo : '—' }}</dd></div>
                <div><dt class="text-gray-500">Médico</dt><dd class="font-medium">{{ prestamo.medico ? `${prestamo.medico.nombre} ${prestamo.medico.apellido_paterno}` : '—' }}</dd></div>
                <div><dt class="text-gray-500">Fecha de salida</dt><dd class="font-medium">{{ fecha(prestamo.fecha_salida) }}</dd></div>
                <div><dt class="text-gray-500">Fecha de regreso</dt><dd class="font-medium">{{ fecha(prestamo.fecha_regreso) }}</dd></div>
            </dl>
            <form @submit.prevent="submit">
                <SelectField v-model="form.recibido_por" label="Recibido por" :options="personalOptions" placeholder="Seleccione personal" :error="form.errors.recibido_por" required />
                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Confirmar devolución</PrimaryButton>
                    <Link :href="route('prestamos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
