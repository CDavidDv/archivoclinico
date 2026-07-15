<script setup>
import { watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { toastExito, toastError } from '@/lib/swal';

const page = usePage();

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) toastExito(flash.success);
        if (flash?.error) toastError(flash.error);
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <!-- Los mensajes flash ahora se muestran como toasts de SweetAlert2. -->
</template>
