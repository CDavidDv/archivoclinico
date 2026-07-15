<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({ links: { type: Array, default: () => [] } });
</script>

<template>
    <div v-if="links.length > 3" class="mt-4 flex flex-wrap gap-1">
        <template v-for="(link, i) in links" :key="i">
            <span
                v-if="!link.url"
                class="rounded px-3 py-1 text-sm text-gray-400"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="rounded px-3 py-1 text-sm"
                :class="link.active ? 'bg-emerald-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'"
                v-html="link.label"
            />
        </template>
    </div>
</template>
