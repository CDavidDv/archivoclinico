<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectField from '@/Components/Field/Select.vue';
import TextField from '@/Components/Field/Text.vue';

const props = defineProps({ expedientes: Array });

const form = useForm({
    id_expediente: '',
    fecha_anexo: new Date().toISOString().slice(0, 10),
    archivos: [],
});

const arrastrando = ref(false);
const expedienteOptions = computed(() =>
    props.expedientes.map((e) => ({ value: e.id, label: e.codigo }))
);

const tamano = (b) => {
    if (b < 1024) return `${b} B`;
    if (b < 1048576) return `${(b / 1024).toFixed(1)} KB`;
    return `${(b / 1048576).toFixed(1)} MB`;
};

const agregar = (fileList) => {
    for (const f of fileList) form.archivos.push(f);
};

const onSelect = (e) => { agregar(e.target.files); e.target.value = ''; };
const onDrop = (e) => { arrastrando.value = false; agregar(e.dataTransfer.files); };
const quitar = (i) => form.archivos.splice(i, 1);
const limpiar = () => (form.archivos = []);

const submit = () => form.post(route('documentos.bulk'), {
    forceFormData: true,
    onSuccess: () => form.reset('archivos'),
});
</script>

<template>
    <AppLayout title="Carga múltiple de documentos">
        <PageHeader title="Carga múltiple de documentos">
            <template #actions>
                <Link :href="route('documentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</Link>
            </template>
        </PageHeader>

        <Card title="Subir varios archivos a la vez">
            <form @submit.prevent="submit">
                <div class="grid gap-4 sm:grid-cols-2">
                    <SelectField v-model="form.id_expediente" label="Expediente" :options="expedienteOptions" placeholder="Seleccione expediente" :error="form.errors.id_expediente" required />
                    <TextField v-model="form.fecha_anexo" label="Fecha de anexo" type="date" :error="form.errors.fecha_anexo" required />
                </div>

                <!-- Zona de arrastrar y soltar -->
                <div
                    class="mt-4 rounded-lg border-2 border-dashed p-8 text-center transition"
                    :class="arrastrando ? 'border-emerald-500 bg-emerald-50' : 'border-gray-300'"
                    @dragover.prevent="arrastrando = true"
                    @dragleave.prevent="arrastrando = false"
                    @drop.prevent="onDrop"
                >
                    <p class="text-sm text-gray-600">Arrastra y suelta los documentos aquí</p>
                    <p class="my-2 text-xs text-gray-400">o</p>
                    <label class="inline-block cursor-pointer rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                        Seleccionar archivos
                        <input type="file" multiple class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.bmp,.webp,.mp4,.avi,.mov,.mkv,.txt,.csv,.zip,.rar"
                            @change="onSelect" />
                    </label>
                    <p class="mt-3 text-xs text-gray-500">Documentos, imágenes, videos y comprimidos (máx. 50 MB c/u).</p>
                    <p v-if="form.errors.archivos" class="mt-2 text-sm text-red-600">{{ form.errors.archivos }}</p>
                </div>

                <!-- Lista de archivos seleccionados -->
                <div v-if="form.archivos.length" class="mt-4">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">{{ form.archivos.length }} archivo(s) seleccionado(s)</span>
                        <button type="button" @click="limpiar" class="text-xs text-red-600 hover:underline">Quitar todos</button>
                    </div>
                    <ul class="divide-y divide-gray-100 rounded-md border border-gray-200 text-sm">
                        <li v-for="(f, i) in form.archivos" :key="i" class="flex items-center justify-between px-3 py-2">
                            <span class="truncate">{{ f.name }}</span>
                            <span class="ml-3 flex items-center gap-3 whitespace-nowrap text-xs text-gray-500">
                                {{ tamano(f.size) }}
                                <button type="button" @click="quitar(i)" class="text-red-600 hover:underline">Quitar</button>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Progreso -->
                <div v-if="form.progress" class="mt-4">
                    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                        <div class="h-2 bg-emerald-600 transition-all" :style="{ width: form.progress.percentage + '%' }"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Subiendo… {{ form.progress.percentage }}%</p>
                </div>

                <div class="mt-6 flex gap-2">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.archivos.length">Subir {{ form.archivos.length || '' }} documento(s)</PrimaryButton>
                    <Link :href="route('documentos.index')" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</Link>
                </div>
            </form>
        </Card>
    </AppLayout>
</template>
