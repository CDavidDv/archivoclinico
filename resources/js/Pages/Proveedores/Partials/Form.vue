<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    form: Object,
});
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <InputLabel for="nombre" value="Nombre" />
            <TextInput id="nombre" v-model="form.nombre" type="text" class="mt-1 block w-full" required />
            <InputError class="mt-1" :message="form.errors.nombre" />
        </div>

        <div>
            <InputLabel for="rfc" value="RFC" />
            <TextInput id="rfc" v-model="form.rfc" type="text" maxlength="13" class="mt-1 block w-full" />
            <InputError class="mt-1" :message="form.errors.rfc" />
        </div>

        <div>
            <InputLabel for="telefono" value="Teléfono" />
            <TextInput id="telefono" v-model="form.telefono" type="text" maxlength="20" class="mt-1 block w-full" />
            <InputError class="mt-1" :message="form.errors.telefono" />
        </div>

        <div>
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
            <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <div class="sm:col-span-2">
            <InputLabel for="direccion" value="Dirección" />
            <TextInput id="direccion" v-model="form.direccion" type="text" class="mt-1 block w-full" />
            <InputError class="mt-1" :message="form.errors.direccion" />
        </div>

        <div class="flex items-center gap-2">
            <Checkbox id="activo" v-model:checked="form.activo" />
            <InputLabel for="activo" value="Activo" class="!mb-0" />
        </div>
    </div>
</template>
