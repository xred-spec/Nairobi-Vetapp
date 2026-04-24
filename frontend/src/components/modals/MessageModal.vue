<script setup lang="ts">
defineProps<{
    message: string,
    Header: string
}>();

defineEmits<{
    (e:'cerrarModal'):void
}>();
</script>

<template>
    <div class="bg-amber-50 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-1/2 h-1/2 flex flex-col items-center">
        <h3 class="text-3xl mt-4 ">{{Header}}</h3>
        <p class="text-2xl mt-3">{{message}}</p>
        <button type="submit" @click="$emit('cerrarModal')" class="btn-style btn-send-edit-data-pet mt-3">Aceptar</button>
    </div>
</template>