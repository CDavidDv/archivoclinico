<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputLabel from '@/Components/InputLabel.vue';

defineProps({
    label: String,
    modelValue: Boolean,
});
defineEmits(['update:modelValue']);
</script>

<template>
    <label class="flex items-center gap-2 cursor-pointer">
        <Checkbox :checked="modelValue" @update:checked="$emit('update:modelValue', $event)" />
        <span class="text-sm text-gray-700 dark:text-gray-300">{{ label }}</span>
    </label>
</template>
