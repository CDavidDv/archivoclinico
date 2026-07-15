<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    label: String,
    modelValue: [String, Number],
    error: String,
    type: { type: String, default: 'text' },
    required: Boolean,
    placeholder: String,
    step: [String, Number],
    min: [String, Number],
    max: [String, Number],
    maxlength: [String, Number],
});
defineEmits(['update:modelValue']);
</script>

<template>
    <div>
        <InputLabel v-if="label" :value="label" />
        <TextInput
            :type="type"
            :required="required"
            :placeholder="placeholder"
            :step="step"
            :min="min"
            :max="max"
            :maxlength="maxlength"
            :model-value="modelValue"
            class="mt-1 block w-full"
            @update:model-value="$emit('update:modelValue', $event)"
        />
        <InputError class="mt-1" :message="error" />
    </div>
</template>
