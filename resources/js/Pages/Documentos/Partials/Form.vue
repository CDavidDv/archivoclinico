<script setup>
import { ref, computed } from 'vue';
import TextField from '@/Components/Field/Text.vue';
import SelectField from '@/Components/Field/Select.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    form: Object,
    expedientes: { type: Array, default: () => [] },
    archivoActual: { type: String, default: '' },
});

const preview = ref(null);

const expedienteOptions = computed(() =>
    props.expedientes.map((e) => ({ value: e.id, label: e.codigo }))
);

const onFile = (event) => {
    const file = event.target.files[0];
    props.form.ruta_archivo = file ?? null;
    preview.value = null;
    if (file && file.type.includes('image')) {
        const reader = new FileReader();
        reader.onload = (e) => (preview.value = e.target.result);
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2">
        <SelectField v-model="form.id_expediente" label="Expediente" :options="expedienteOptions" placeholder="Seleccione expediente" :error="form.errors.id_expediente" required />
        <TextField v-model="form.fecha_anexo" label="Fecha de anexo" type="date" :error="form.errors.fecha_anexo" required />
        <div class="sm:col-span-2">
            <TextField v-model="form.nombre_documento" label="Nombre del documento" placeholder="Ej. INE, Acta de nacimiento, Receta médica..." :error="form.errors.nombre_documento" required />
        </div>
        <div class="sm:col-span-2">
            <InputLabel value="Archivo" />
            <input
                type="file"
                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.webp,.mp4,.avi,.mov,.mkv,.txt,.csv,.zip,.rar"
                class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:rounded-md file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-white hover:file:bg-emerald-700"
                @change="onFile"
            />
            <p class="mt-1 text-xs text-gray-500">Documentos, imágenes, videos y comprimidos (máx. 50 MB).</p>
            <p v-if="archivoActual" class="mt-1 text-xs text-gray-500">Actual: {{ archivoActual }} — deja vacío para conservarlo.</p>
            <InputError class="mt-1" :message="form.errors.ruta_archivo" />
            <img v-if="preview" :src="preview" class="mt-3 max-h-64 rounded border" />
        </div>
    </div>
</template>
