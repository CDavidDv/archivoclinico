<script setup>
defineProps({
    title: String,
    subtitle: String,
});
</script>

<template>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700 dark:text-emerald-400">{{ title }}</h1>
            <p v-if="subtitle" class="text-sm text-gray-500 dark:text-gray-400">{{ subtitle }}</p>
        </div>
        <div class="flex items-center gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
