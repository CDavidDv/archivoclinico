<script setup>
import { computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextareaField from '@/Components/Field/Textarea.vue';
import CheckboxField from '@/Components/Field/CheckboxField.vue';

const props = defineProps({
    form: Object,
    categorias: { type: Array, default: () => [] },
});

const cap = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : s);
const categoriaOptions = computed(() => props.categorias.map((c) => ({ value: c, label: cap(c) })));
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-3">
        <TextField v-model="form.clave" label="Clave" :error="form.errors.clave" required maxlength="30" />
        <div class="sm:col-span-2">
            <TextField v-model="form.nombre" label="Nombre" :error="form.errors.nombre" required />
        </div>
        <SelectField v-model="form.categoria" label="Categoría" :options="categoriaOptions" :error="form.errors.categoria" required />
        <TextField v-model="form.unidad_medida" label="Unidad de medida" :error="form.errors.unidad_medida" required maxlength="30" />
        <TextField v-model="form.stock_minimo" label="Stock mínimo" type="number" min="0" :error="form.errors.stock_minimo" required />
        <div class="sm:col-span-2">
            <TextareaField v-model="form.descripcion" label="Descripción" :error="form.errors.descripcion" />
        </div>
        <div class="flex items-end">
            <CheckboxField v-model="form.activo" label="Activo" />
        </div>
    </div>
</template>
