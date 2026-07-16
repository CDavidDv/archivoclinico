<script setup>
import { ref, computed, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

/*
 * Selector con búsqueda: el usuario escribe (nombre o clave) y la lista se
 * filtra. Filtrado en cliente sobre las opciones ya cargadas.
 *
 * options: [{ value, label }]
 */
const props = defineProps({
    modelValue: [String, Number],
    options: { type: Array, default: () => [] },
    label: { type: String, default: '' },
    placeholder: { type: String, default: 'Escribe para buscar…' },
    error: { type: String, default: '' },
    required: { type: Boolean, default: false },
});
const emit = defineEmits(['update:modelValue']);

const query = ref('');
const abierto = ref(false);
const resaltado = ref(0);

const seleccion = computed(() => props.options.find((o) => o.value === props.modelValue) || null);

// Muestra la etiqueta seleccionada cuando el menú está cerrado.
watch(seleccion, (s) => { if (!abierto.value) query.value = s ? s.label : ''; }, { immediate: true });

const filtradas = computed(() => {
    const q = query.value.trim().toLowerCase();
    if (!q || (seleccion.value && query.value === seleccion.value.label)) return props.options;
    return props.options.filter((o) => o.label.toLowerCase().includes(q));
});

const abrir = () => { abierto.value = true; resaltado.value = 0; };
const cerrar = () => {
    abierto.value = false;
    query.value = seleccion.value ? seleccion.value.label : '';
};
// Retrasa el cierre para permitir el click en una opción (mousedown la elige).
const onBlur = () => setTimeout(cerrar, 150);

const elegir = (o) => {
    emit('update:modelValue', o.value);
    query.value = o.label;
    abierto.value = false;
};

const onInput = () => {
    abierto.value = true;
    resaltado.value = 0;
    if (props.modelValue) emit('update:modelValue', ''); // limpia selección al reescribir
};

const onEnter = () => {
    if (abierto.value && filtradas.value[resaltado.value]) elegir(filtradas.value[resaltado.value]);
};
const mover = (paso) => {
    if (!abierto.value) { abrir(); return; }
    const n = filtradas.value.length;
    if (n) resaltado.value = (resaltado.value + paso + n) % n;
};
</script>

<template>
    <div class="relative">
        <InputLabel v-if="label" :value="label" />
        <input
            type="text"
            v-model="query"
            :placeholder="placeholder"
            :required="required && !modelValue"
            autocomplete="off"
            @focus="abrir"
            @input="onInput"
            @keydown.down.prevent="mover(1)"
            @keydown.up.prevent="mover(-1)"
            @keydown.enter.prevent="onEnter"
            @keydown.esc="cerrar"
            @blur="onBlur"
            class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"
        />
        <ul v-if="abierto && filtradas.length"
            class="absolute z-20 mt-1 max-h-56 w-full overflow-auto rounded-md border border-gray-200 bg-white py-1 text-sm shadow-lg dark:border-gray-600 dark:bg-gray-800">
            <li v-for="(o, i) in filtradas" :key="o.value"
                @mousedown.prevent="elegir(o)"
                @mouseenter="resaltado = i"
                class="cursor-pointer px-3 py-1.5"
                :class="i === resaltado ? 'bg-emerald-600 text-white' : 'text-gray-700 dark:text-gray-200'">
                {{ o.label }}
            </li>
        </ul>
        <div v-if="abierto && !filtradas.length"
            class="absolute z-20 mt-1 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-400 shadow-lg dark:border-gray-600 dark:bg-gray-800">
            Sin coincidencias
        </div>
        <InputError v-if="error" class="mt-1" :message="error" />
    </div>
</template>
