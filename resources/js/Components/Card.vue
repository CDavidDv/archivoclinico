<script setup>
defineProps({
    title: String,
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div v-if="title || $slots.header" class="bg-emerald-700 text-white px-4 py-3 flex items-center justify-between">
            <span class="font-semibold">{{ title }}</span>
            <slot name="header" />
        </div>
        <div class="p-4 sm:p-6">
            <slot />
        </div>
    </div>
</template>
