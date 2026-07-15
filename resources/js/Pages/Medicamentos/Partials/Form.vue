<script setup>
import { computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';
import CheckboxField from '@/Components/Field/CheckboxField.vue';

const props = defineProps({
    form: Object,
    productos: { type: Array, default: () => [] },
});

const productoOptions = computed(() =>
    props.productos.map((p) => ({ value: p.id, label: `${p.clave} — ${p.nombre}` }))
);
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-3">
        <TextField v-model="form.clave" label="Clave" :error="form.errors.clave" required maxlength="30" />
        <div class="sm:col-span-2">
            <TextField v-model="form.nombre" label="Nombre" :error="form.errors.nombre" required />
        </div>
        <div class="sm:col-span-2">
            <TextField v-model="form.sustancia_activa" label="Sustancia activa" :error="form.errors.sustancia_activa" required />
        </div>
        <TextField v-model="form.presentacion" label="Presentación" placeholder="Tabletas 500 mg, caja c/20" :error="form.errors.presentacion" required maxlength="100" />
        <TextField v-model="form.unidad_medida" label="Unidad de medida" :error="form.errors.unidad_medida" required maxlength="30" />
        <TextField v-model="form.stock_minimo" label="Stock mínimo" type="number" min="0" :error="form.errors.stock_minimo" required />
        <div>
            <SelectField v-model="form.id_producto" label="Producto de almacén vinculado" :options="productoOptions" placeholder="Sin vínculo" :error="form.errors.id_producto" />
            <p class="mt-1 text-xs text-gray-500">Necesario para recibir transferencias del almacén.</p>
        </div>
        <div class="flex items-end">
            <CheckboxField v-model="form.activo" label="Activo" />
        </div>
    </div>
</template>
