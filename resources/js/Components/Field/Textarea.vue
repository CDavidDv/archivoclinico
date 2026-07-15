<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    label: String,
    modelValue: String,
    error: String,
    rows: { type: [String, Number], default: 2 },
    required: Boolean,
    placeholder: String,
});
defineEmits(['update:modelValue']);
</script>

<template>
    <div>
        <InputLabel v-if="label" :value="label" />
        <textarea
            :rows="rows"
            :required="required"
            :placeholder="placeholder"
            :value="modelValue"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300"
            @input="$emit('update:modelValue', $event.target.value)"
        ></textarea>
        <InputError class="mt-1" :message="error" />
    </div>
</template>
