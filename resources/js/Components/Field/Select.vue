<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    label: String,
    modelValue: [String, Number, null],
    error: String,
    required: Boolean,
    // Array of { value, label } or plain strings.
    options: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Selecciona...' },
    includeBlank: { type: Boolean, default: true },
});
defineEmits(['update:modelValue']);

const opt = (o) => (typeof o === 'object' && o !== null ? o : { value: o, label: o });
</script>

<template>
    <div>
        <InputLabel v-if="label" :value="label" />
        <select
            :required="required"
            :value="modelValue"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option v-if="includeBlank" value="">{{ placeholder }}</option>
            <option v-for="o in options" :key="opt(o).value" :value="opt(o).value">{{ opt(o).label }}</option>
        </select>
        <InputError class="mt-1" :message="error" />
    </div>
</template>
